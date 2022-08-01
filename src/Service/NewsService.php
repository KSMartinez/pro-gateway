<?php

namespace App\Service;

use App\Entity\News;
use App\Repository\NewsRepository;
use DateTime;

class NewsService
{
    const NUMBER_OF_RANDOM_EVENTS_TO_DISPLAY = 6;

    /**
     * @param NewsRepository $newsRepository
     */
    public function __construct(
        private NewsRepository $newsRepository
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
}