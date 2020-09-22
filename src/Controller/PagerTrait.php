<?php

namespace App\Controller;

use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;

trait PagerTrait
{
    public function jsonPager(Pagerfanta $data): JsonResponse
    {
        return $this->json(['data' => $data, 'meta' => [
            'limit' => $data->getMaxPerPage(),
            'total_items' => $data->getNbResults(),
            'page_number' => $data->getCurrentPage(),
            ],
        ]);
    }
}
