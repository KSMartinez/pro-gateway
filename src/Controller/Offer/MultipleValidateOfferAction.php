<?php

namespace App\Controller\Offer;

use App\Service\OfferService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MultipleValidateOfferAction extends AbstractController
{
    public function __construct(private OfferService $offerService)
    {
    }


    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function __invoke(Request $request): Response
    {

        $ids = $request->query->all();

        if (empty($ids)){
            throw new Exception('No ids to validate');
        }


        /** @var string $idsToValidate comma seperated IDs */
        $idsToValidate = $ids['ids'];
        $ids = explode(',', $idsToValidate);
        $this->offerService->validateMultipleOffers($ids);

        return new Response('Done', 204);
    }

}