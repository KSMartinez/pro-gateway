<?php


namespace App\Service;

use App\Entity\News;
use App\Repository\NewsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;

class NewsService
{
    const NUMBER_OF_RANDOM_EVENTS_TO_DISPLAY = 6;
    const CONTENT_TYPE_JSON = 'json';
    const DATA_JSON_PARAM = 'pathImg';

    /**
     * @param NewsRepository $newsRepository
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private NewsRepository $newsRepository,
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @return News[]
     */
    public function randomNewsList()
    {

        $news = array();
        $result = array();

        $allNews = $this->newsRepository->allNews();

        $rangeMax = count($allNews) - 1;

        if (count($allNews) > self::NUMBER_OF_RANDOM_EVENTS_TO_DISPLAY) {

            # Let's generate 6 Random events
            while (count($news) != self::NUMBER_OF_RANDOM_EVENTS_TO_DISPLAY) {

                for ($i = 0; $i < count($allNews); $i++) {

                    $forRandomNews = mt_rand(0, $rangeMax);

                    if ($i == $forRandomNews) {

                        if (!in_array($allNews[$i], $news)) {

                            array_push($news, $allNews[$i]);

                        }
                    }
                }

            }

            return $this->reOrderTable($news, $result);

        }

        return $this->reOrderTable($allNews, $result);


    }

    /**
     * @param News[] $news
     * @param News[] $result
     * @return News[]
     */
    public function reOrderTable(array $news, array $result)
    {

        $array_dateTimes = array();

        for ($i = 0; $i < count($news); $i++) {

            $dateOfPublication = $news[$i]->getPublishedAt();
            if($dateOfPublication !== null) {
                $val1 = DateTime::createFromInterface($dateOfPublication);
                $array_dateTimes[$i] = $val1->format('Y-m-d H:i:s');
            }
        }

        usort($array_dateTimes, function ($time1, $time2) {

            if (strtotime($time1) < strtotime($time2))
                return 1;

            else if (strtotime($time1) > strtotime($time2))
                return -1;
            else
                return 0;
        });


        usort($array_dateTimes, function ($time1, $time2) {

            $hours1 = substr($time1, 11, -6);
            $minutes1 = substr($time1[0], 14, -3);

            $hours2 = substr($time2, 11, -6);
            $minutes2 = substr($time2[0], 14, -3);

            if (intval($hours1) < intval($hours2)) {
                return 1;
            } else if (intval($hours1) > intval($hours2)) {
                return -1;
            } else {
                if (intval($minutes1) < intval($minutes2)) {
                    return 1;
                } else if (intval($minutes1) > intval($minutes2)) {
                    return -1;
                } else {
                    return 0;
                }

            }

        });

        foreach ($array_dateTimes as $date) {
            foreach ($news as $new) {
                $dateOfPub = $new->getPublishedAt();
                if($dateOfPub !== null) {
                    $dateTime = DateTime::createFromInterface($dateOfPub)->format('Y-m-d H:i:s');

                    if (strcmp($dateTime, $date) == 0) {
                        if (!in_array($new, $result)) {
                            array_push($result, $new);
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return News
     * @throws Exception
     */
    public function updatePicture(): News
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof News)) {
            throw new \RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The object should have an id for updating');
        }
        if (!$this->newsRepository->find($object->getId())) {
            throw new Exception('The object should have an id for updating');
        }

        $file = $request->files->get('imageFile');

        if ($file instanceof File) {
            $object->setFile($file);
            $object->setUpdatedAt(new \DateTime());
        }

        return $object;
    }

    /**
     * @return News|void
     * @throws Exception
     */
    public function updateImageStock()
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof News)) {
            throw new \RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The object should have an id for updating');
        }
        if (!$this->newsRepository->find($object->getId())) {
            throw new Exception('The object should have an id for updating');
        }

        if ($request->getContentType() === self::CONTENT_TYPE_JSON) {
            $arrayDataJson = json_decode($request->getContent(), true);
            if (is_array($arrayDataJson)) {
                $pathFilename = $arrayDataJson[self::DATA_JSON_PARAM];
                $object->setImagePath($pathFilename);
                $this->entityManager->flush();

                return $object;
            }
            throw new Exception();
        }
    }
}