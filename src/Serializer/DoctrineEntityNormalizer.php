<?php

namespace App\Serializer;

use App\Entity\Domain;
use App\Entity\LevelOfEducation;
use App\Entity\OfferCategory;
use App\Entity\SectorOfOffer;
use App\Entity\TypeOfContract;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 *
 */
class DoctrineEntityNormalizer implements ContextAwareDenormalizerInterface
{

    /**
     * @var string[]
     */
    private array $classesToDenormalize = [
        TypeOfContract::class,
        Domain::class,
        OfferCategory::class,
        SectorOfOffer::class,
        LevelOfEducation::class
    ];

    use DenormalizerAwareTrait;

    /**
     * @param ObjectNormalizer $denormalizer
     * @param ManagerRegistry  $doctrine
     */
    public function __construct(ObjectNormalizer $denormalizer, protected ManagerRegistry $doctrine)
    {
        $this->setDenormalizer($denormalizer);
        $this->setDoctrine($doctrine);
    }

    /**
     * @template TEntityClass of object
     * @param array<mixed>               $data
     * @param class-string<TEntityClass> $type
     * @param string|null                $format
     * @param array<mixed>               $context
     * @return bool
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        if (!in_array($type, $this->classesToDenormalize)) {
            return false;
        }

        if ($this->denormalizer === null) {
            throw new BadMethodCallException(sprintf('The nested denormalizer needs to be set to allow "%s()" '
                                                     . 'to be used.', __METHOD__));
        }

        $repository = $this->getRepository($type);
        // Check that it s an Entity of our App and a Repository exist for it
        // Also only use the denormalizer if an ID is set to load from the Repository.
        return !is_null($repository) || (is_array($data) && isset($data['label']));
    }

    /**
     * @template TEntityClass of object
     * @param array<mixed>               $data
     * @param class-string<TEntityClass> $type
     * @param string|null                $format
     * @param array<mixed>               $context
     * @return mixed
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {

        if ($this->denormalizer === null) {
            throw new BadMethodCallException('Please set a denormalizer before calling denormalize');
        }

        $repository = $this->getRepository($type);
        if (!$repository instanceof ObjectRepository) {
            throw new InvalidArgumentException('No repository found for the type ' . $type);
        }

        $entity = null;


        if (is_array($data)) {

            $entity = $repository->findOneBy(['label' => $data['label']]);
        }

        if (is_null($entity)) {
            throw new InvalidArgumentException('No Entity found for given type ' . $type . ' with value ' . $data['label']);
        }

        $tempContext = array_merge($context, [
            AbstractNormalizer::OBJECT_TO_POPULATE => $entity,
        ]);

        return $this->denormalizer->denormalize($data, $type, $format, $tempContext);

    }

    /**
     * @return ManagerRegistry
     */
    public function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }

    /**
     * @param ManagerRegistry $doctrine
     */
    public function setDoctrine(ManagerRegistry $doctrine): void
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @template TEntityClass of object
     * @param class-string<TEntityClass> $type
     * @return ObjectRepository<TEntityClass>|null
     */
    private function getRepository(string $type): ?ObjectRepository
    {
        try {
            $entityManager = $this->getDoctrine()
                                  ->getManagerForClass($type);
            if (!is_null($entityManager)) {
                return $entityManager->getRepository($type);
            }
        } catch (Exception $exception) {

        }


        return null;
    }
}