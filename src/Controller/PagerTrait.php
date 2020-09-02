<?php


namespace App\Controller;


use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;

Trait PagerTrait
{
    public function jsonPager(Pagerfanta $data) : JsonResponse
    {
        return $this->json(['data' => $data, 'meta' => [
            'limit' => $data->getMaxPerPage(),
            'current_items' => count($data->getCurrentPageResults()),
            'total_items' => $data->getNbResults(),
            'offset' => $data->getCurrentPageOffsetStart()

        ]]);
    }
}