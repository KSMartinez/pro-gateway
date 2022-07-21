<?php

namespace App\Service;

use App\Entity\Offer;
use App\Entity\OfferStatus;
use App\Entity\SectorOfOffer;
use App\Entity\TypeOfContract;
use App\Repository\OfferStatusRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use XMLParser;


/**
 *
 */
class XMLParseService
{
    /**
     * @var string|null
     */
    private ?string $currentTag = null;
    /**
     * @var bool
     */
    private bool $isCurrentOffer = false;
    /**
     * @var Offer
     */
    private Offer $offer;
    /**
     * @var int
     */
    private int $unPersistedCount = 0;

    /**
     * @param EntityManagerInterface $entityManager
     * @param string                 $projectDir
     */
    public function __construct(private EntityManagerInterface $entityManager, private string $projectDir, private OfferStatusRepository $offerStatusRepository)
    {
    }

    /**
     * @param string $filepath
     * @return void
     * @throws Exception
     */
    public function parseXML(string $filepath = '')
    {
        //open a stream to the file
        $stream = fopen($this->projectDir . DIRECTORY_SEPARATOR . $_ENV["XML_IMPORT_DIR"] . '\e2.xml', 'r');

        if (!$stream) {
            throw new Exception('Could not open the specified XML file');
        }

        $parser = xml_parser_create();

        // use case-folding so we are sure to find the tag in $map_array
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 1);
        //option to skip whitespaces does not work
        //xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

        //define handler for when start element and end element and characters are encountered by the parser
        xml_set_element_handler($parser, [$this, 'startElement'], [$this, 'endElement']);
        xml_set_character_data_handler($parser, [$this, "characterData"]);

        //parse the data in chunks so as to not load the entire file onto memory
        while (($data = fread($stream, 16384))) {
            xml_parse($parser, $data); // parse the current chunk
        }

        //last line of parsing
        xml_parse($parser, '', true); // finalize parsing
        xml_parser_free($parser);
        fclose($stream);
    }

    /** The handler for start tag.
     * When a start tag for an offer is read by the parser, we create a new Offer object. We add all the values till we
     * encounter the end tag of that particular offer. This is continued.
     *
     * @param bool|XMLParser $parser
     * @param string         $name
     * @param array<mixed>   $attrs
     * @return void
     * @throws Exception
     */
    function startElement(bool|XMLParser $parser, string $name, array $attrs)
    {
        //Store the current tag that is being processed
        $this->currentTag = $name;

        $enAttenteStatus = $this->getOfferStatus(OfferStatus::ATTENTE_DE_VALIDATION);

        //todo refactor and fix
        $typeOfContract = $this->entityManager->getRepository(TypeOfContract::class)
                                              ->findOneBy(array('label' => TypeOfContract::CDI));
        if (!$typeOfContract){
            throw new Exception("Type of Contract " . TypeOfContract::CDI . ' not found.');
        }


        if ($name === 'OFFRE_EMPLOI') {
            $this->isCurrentOffer = true;
            $this->offer = new Offer();

            //set the mandatory with some default values
            $this->offer->setTitle('UNDEFINED')
                        ->setOfferId('-')
                        ->setOfferStatus($enAttenteStatus)
                        ->setTypeOfContract($typeOfContract);
        }


    }

    /**
     * @param bool|XMLParser $parser
     * @param string         $name
     * @return void
     */
    function endElement(bool|XMLParser $parser, string $name)
    {
        //if the end tag is of the offer, we can persist and flush the offer to the database
        if ($name === 'OFFRE_EMPLOI') {
            $this->entityManager->persist($this->offer);
            $this->unPersistedCount++;

            // We flush in batches for efficiency of database overhead
            if ($this->unPersistedCount == 5) {
                $this->entityManager->flush();
                $this->unPersistedCount = 0;
            }

            //we are no longer processing an offer until the next offer tag starts so set it to false
            $this->isCurrentOffer = false;
        }

    }


    /**
     * @param bool|XMLParser $parser
     * @param string         $data
     * @return void
     */
    private function characterData(bool|XMLParser $parser, string $data)
    {
        //If we're not inside an offer block, we are not interested in the data.
        if (!$this->isCurrentOffer) {
            return;
        }

        // If the data is empty, we are not interested in the data.
        if (empty(trim($data))) {
            return;
        }

        /**
         * We check each tag with the set of keys we know and add it to the correct field in the offer.
         * Note that the currentTag is ALWAYS in UPPERCASE
         */
        switch ($this->currentTag) {
            case 'INTITULE':
                $this->offer->setTitle($data);
                break;
            case 'DATE_PARUTION':
                $date = DateTime::createFromFormat('d/m/Y H:i:s', $data);
                if (!$date) {
                    break;
                }
                $this->offer->setDatePosted($date);
                break;
            case 'NIVEAU_EXPERIENCE':
                $this->offer->setExperience($data);
                break;
            case 'LIBELLE_SECTEUR':
                $this->offer->setSector($this->entityManager->getRepository(SectorOfOffer::class)
                                                            ->findOneBy(array('label' => SectorOfOffer::ACTION_SOCIALE_SANS_HEBERGEMENT)));
                break;
            case 'TEXTE_OFFRE':
                if (strlen($data) > 250) {
                    //todo fix the description field in the table and remove this check.
                    $this->offer->setDescription('The description is too long to fit in the table. To be fixed.');
                } else {

                    $this->offer->setDescription($data);
                }
                break;
            case 'NOM_ENTREPRISE':
                $this->offer->setCompanyName($data);
                break;
            case 'URL':
                $this->offer->setUrlCompany($data);
                break;
            case 'REFERENCE_APEC':
                $this->offer->setOfferId($data);
                break;
        }

    }

    /**
     * @param string $status
     * @return OfferStatus
     * @throws Exception
     */
    private function getOfferStatus(string $status): OfferStatus
    {
        $offerStatus = $this->offerStatusRepository->findOneBy(array('label' => $status));
        if (!$offerStatus) {
            throw new Exception('OfferStatus with label ' . $status . ' was not found in the table. Please add it correctly.');
        }
        return $offerStatus;
    }

}