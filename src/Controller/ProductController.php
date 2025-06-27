<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Services\cartService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProductController extends AbstractController
{
    #[Route('/product/create', name: 'product_create')]
    #[IsGranted('ROLE_ADMIN or ROLE_SUPER_ADMIN')]
    public function createProduct(Request $request, ProductRepository $productRepository, ImageRepository $imageRepository, CartService $cartService): Response
    {
        $product = new Product();
        $image = new Image();

        if ($request->isMethod("POST")) {
            $uploadedFile = $request->files->get('file');

            if ($uploadedFile) {

                try {
                    $uploadsDirectory = 'uploads/images/products/';
                    $filename = $request->request->get("title") . '.' . $uploadedFile->guessExtension();

                    $product->setName($request->request->get("title"));
                    $product->setDescription($request->request->get("description"));
                    $product->setPrice($request->request->get("price"));
                    $product->setImagePath($uploadsDirectory . $filename);

                    $image->setName($filename);
                    $image->setFilePath($uploadsDirectory . $filename);
                    $uploadedFile->move($uploadsDirectory, $filename);

                    $productRepository->save($product, true);
                    $imageRepository->save($image, true);

                    $this->addFlash('success', 'File uploaded successfully!');
                    return $this->redirectToRoute('product_create'); // ou autre route
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload file: ' . $e->getMessage());
                    return $this->redirectToRoute('file_upload_form');
                }

            } else {
                $this->addFlash('error', 'No file selected.');
            }
        }
        return $this->render('product/editProduct.html.twig', ['product' => $product]);
    }

    #[Route('/product/update/{product}', name: 'product_update')]
    #[IsGranted('ROLE_ADMIN or ROLE_SUPER_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN or ROLE_SUPER_ADMIN')]
    public function deleteProduct(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        return $this->render('product/editProduct.html.twig', ['product' => $product]);
    }
}
