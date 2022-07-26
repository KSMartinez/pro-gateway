<?php

namespace App\Service;

use ApiPlatform\Core\Serializer\JsonEncoder;
use App\Entity\Candidature;
use App\Entity\LevelOfEducation;
use App\Entity\Offer;
use App\Repository\LevelOfEducationRepository;
use App\Serializer\DoctrineEntityNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
class NexusAPIService
{
    //todo move to env file
    /**
     *
     */
    const API_URL = 'http://127.0.0.1:8081/api/';
    /**
     *
     */
    const CURRENT_UNI = "XYZ Uni";

    /**
     * @param DoctrineEntityNormalizer $doctrineEntityNormalizer
     * @param HttpClientInterface      $httpClient
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param string                   $projectDir
     */
    public function __construct(private DoctrineEntityNormalizer $doctrineEntityNormalizer, private HttpClientInterface $httpClient, private EntityManagerInterface $entityManager, private LoggerInterface $logger, private string $projectDir)
    {
    }

    /**
     * @param Candidature $candidature
     * @return void
     */
    public function executeCommandToSendCandidature(Candidature $candidature)
    {
        $process = new Process(['php', 'bin/console', 'app:nexus:api', $candidature->getId()]);
        $process->setWorkingDirectory($this->projectDir);

        try {

            $process->run();
        } catch (Exception $exception) {
            $this->logger->error("Process could not be launched. Error: " . $exception->getMessage());
        }

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $this->logger->error("Process running command failed. Error: " . $process->getErrorOutput());
            throw new ProcessFailedException($process);
        }

    }

    /**
     * @param mixed $candidatureId
     * @return void
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws Exception
     */
    public function dispatchCandidatureToNexus(mixed $candidatureId)
    {
        $candidatureUrl = 'candidatures';
        $candidature = $this->entityManager->getRepository(Candidature::class)
            ->find($candidatureId);
        if (!$candidature){
            throw new Exception('Cannot find candidature with id ' . $candidatureId);
        }
        $candidatureArray = $candidature->toArray();

        $candidatureArray = $this->prepBodyOfRequestForCandidature($candidatureArray);
        try {
            $res = $this->httpClient->request(
                'POST',
                self::API_URL . $candidatureUrl,
                [
                    'body' => json_encode($candidatureArray),
                    'headers' => ['Content-Type' => 'application/json']
                ]
            );

            if ($res->getStatusCode() != 201){
                $this->logger->error('Request to create candidature with Nexus returned with return code ' . $res->getStatusCode() . ' and content ' . $res->getContent());
            }

        } catch (Exception $e) {

            $this->logger->error('Exception while sending candidature to Nexus ' . $e->getMessage());
        }
    }

    /**
     * @param array<mixed> $candidatureArray
     * @return mixed[]
     */
    private function prepBodyOfRequestForCandidature(array $candidatureArray): array
    {
        //add university to user
        $userID = $candidatureArray['user'];
        $candidatureArray['user'] = [
            'userId' => $userID,
            'university' => [
                'name' => self::CURRENT_UNI
            ]
        ];
        $candidatureArray['candidatureId'] = $candidatureArray['id'];
        unset($candidatureArray['id']);
        return $candidatureArray;

    }

    /**
     * @return Offer[]
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function requestNexusForOffers(): array
    {
        $offerUrl = 'offers';

        $res = $this->httpClient->request(
            'GET',
            self::API_URL . $offerUrl,
            [
                'headers' => ['accept' => 'application/json']
            ]
        );

        /*if ($res->getStatusCode() != 201){
            $this->logger->error('Request to create candidature with Nexus returned with return code ' . $res->getStatusCode() . ' and content ' . $res->getContent());
        }*/


        $offers = $res->getContent();
        /** @var LevelOfEducationRepository $repo */
        $repo = $this->entityManager->getRepository(LevelOfEducation::class);
        //dd($offers);
        $serializer = new Serializer(
            [new DateTimeNormalizer(), $this->doctrineEntityNormalizer, new ArrayDenormalizer(), new ObjectNormalizer(null,
                                                                                                                      null,
                                                                                                                      null,
                                                                                                                      new ReflectionExtractor())],
            [new JsonEncoder('json')]
        );


        return $serializer->deserialize($offers, 'App\Entity\Offer[]', 'json');
    }
}