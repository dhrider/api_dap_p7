<?php

namespace App\Controller;

use App\Repository\ProductRepository;
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
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function list(ProductRepository $productRepository, Request $request) : JsonResponse
    {
        $page = $request->query->get('page', 1);
        $adapter = new QueryAdapter($productRepository->findAllProducts(), false);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(2);

        try {
            $pager->setCurrentPage($page);
            return $this->jsonPager($pager);
        } catch (OutOfRangeCurrentPageException $exception) {
            return $this->json('Page doesn\'t exist', 404);
        }
    }

    /**
     * @Route("/api/product/{id}", name="product_show", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param $id
     * @return JsonResponse
     */
    public function show(ProductRepository $productRepository, $id) : JsonResponse
    {
        return $this->json($productRepository->find($id), 200, []);
    }
}
