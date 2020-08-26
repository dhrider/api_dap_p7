<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="products_list", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function products(ProductRepository $productRepository)
    {
        return $this->json($productRepository->findAll(), 200, []);
    }


}
