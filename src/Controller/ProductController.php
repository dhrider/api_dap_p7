<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\SerializerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    use PagerTrait;

    /**
     * @Route("/api/products", name="products_list", methods={"GET"})
     *
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function list(ProductRepository $productRepository, Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $adapter = new QueryAdapter($productRepository->findAllProducts(), false);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(2);

        try {
            $pager->setCurrentPage($page);

            return $this->jsonPager($pager);
        } catch (OutOfRangeCurrentPageException $exception) {
            return $this->json('La page n\'existe pas !', 404);
        }
    }

    /**
     * @Route("/api/products/{id}", name="products_show", methods={"GET"})
     *
     * @param ProductRepository $productRepository
     * @param $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(ProductRepository $productRepository, $id, SerializerInterface $serializer): JsonResponse
    {
        try {
            $json = $serializer->serialize($productRepository->find($id), 'json');
            return New JsonResponse($json, 200, ['Content-Type' => 'application/json'], true);
        } catch (NotAcceptableException $exception) {
            return new JsonResponse('Le produit n\'existe pas !', 404);
        }
    }
}
