<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product/create', name: 'product_create')]
    public function createProduct(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();

        if ($request->isMethod("POST")) {

            $product->setName($request->request->get("title"));
            $product->setDescription($request->request->get("description"));
            $product->setPrice($request->request->get("price"));

            $productRepository->save($product, true);
        }

        return $this->render('product/editProduct.html.twig', ['product' => $product]);
    }

    #[Route('/product/update/{product}', name: 'product_update')]
    public function updateProduct(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($request->isMethod("POST")) {

            $product->setName($request->request->get("title"));
            $product->setDescription($request->request->get("description"));
            $product->setPrice($request->request->get("price"));

            $productRepository->save($product, true);
        }

        return $this->render('product/editProduct.html.twig', ['product' => $product]);
    }

    #[Route('/product/', name: 'product_list')]
    public function listProduct(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/listProducts.html.twig', ['products' => $products]);
    }

    #[Route('/product/delete/{product}', name: 'product_delete')]
    public function deleteProduct(Request $request, Product $product, ProductRepository $productRepository): Response
    {


        return $this->render('product/editProduct.html.twig', ['product' => $product]);
    }
}
