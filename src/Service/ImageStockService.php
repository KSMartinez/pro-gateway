<?php

namespace App\Service;

use App\DataProvider\ImageStockDataProvider;
use Exception;


class ImageStockService
{

    public function __construct(private ImageStockDataProvider $imageStockDataProvider)
    {
    }

    /**
     * @param string $imageStockIdReceived
     * @return string|null
     * @throws Exception|Exception
     */
    public function imageStockIdExist (string $imageStockIdReceived): ?string
    {
        $arrayImagesStock = $this->imageStockDataProvider->getCollection('App\Entity\ImageStock');
        if(count($arrayImagesStock) > 0 ) {
            foreach ($arrayImagesStock as $imageStock) {
                if($imageStock->getId() === $imageStockIdReceived ) {
                    return $imageStock->getResourceUrl();
                }
            }
        }

        return null;
    }
}