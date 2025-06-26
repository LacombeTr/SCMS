<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Services\cartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(Request $request, cartService $cartService, ProductRepository $productRepository): ?Response
    {
        if ($request->isMethod("POST")) {
            $localCart = json_decode($request->getContent(), true);

            $cart = $cartService->priceChecker($localCart, $productRepository);

            // On sauvegarde dans la session
            $request->getSession()->set('cart', $cart);
        }
        // Requête GET classique => on affiche le panier s'il est en session
        $cart = $request->getSession()->get('cart', []);

        return $this->render('cart/index.html.twig', ['cart' => $cart]);
    }
}
