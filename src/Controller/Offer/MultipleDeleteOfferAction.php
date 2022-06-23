<?php

namespace App\Controller\Offer;

use App\Service\OfferService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class MultipleDeleteOfferAction extends AbstractController
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
            throw new Exception('Ids are empty');
        }

        /** @var string $idsToDelete */
        $idsToDelete = $ids['ids'];
        $ids = explode(',', $idsToDelete);
        $this->offerService->deleteMultipleOffers($ids);
        return new Response('OK', 204);
    }

}