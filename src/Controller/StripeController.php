<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Services\cartService;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class StripeController extends AbstractController
{
    public function __construct(private ParameterBagInterface $params)
    {
    }

    #[Route('/stripe', name: 'app_stripe')]
    //#[IsGranted('SUPER_ADMIN')] TODO Ajouter ?
    public function createLink(Request $request, ProductRepository $productRepository, cartService $cartService): ?\Symfony\Component\HttpFoundation\RedirectResponse
    {

        if ($request->isMethod("POST")) {

            // On récupère les données du panier à l'aide d'un post
            $localCart = json_decode($request->getContent(), true);

            $cart = $cartService->priceChecker($localCart, $productRepository);

            // On itère dans a travers le panier pour recuprer les prix associés aux ID
            $totalPrice = 0;

            foreach ($cart as $key => $value) {
                $price = ($value["price"]) * ($value["quantity"]);
                $totalPrice = $totalPrice + ($price * 100);
            }

            //$totalPrice = 1000; // EN CENTIMES !!!!!

            $secretKey = $_ENV['STRIPE_PRIVATE_SECRET_KEY'] ?? null;

            if (!$secretKey) {
                throw new \RuntimeException('STRIPE_PRIVATE_SECRET_KEY manquante.');
            }

            $stripe = new StripeClient($secretKey);

            $price = $stripe->prices->create([
                'currency' => 'eur',
                'unit_amount' => $totalPrice,
                'product_data' => ['name' => 'Votre panier'],
            ]);
            $price = $price->values();
            $paymentLink = $stripe->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $price[0],
                        'quantity' => 1,
                    ],
                ],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => ['url' => 'https://localhost:8000'],
                ],
            ]);

            $paymentLink = $paymentLink->values();

            return $this->redirect($paymentLink[31]);
        } else {
            return null;
        }

    }

//  TODO: integrer le WebHook de Stripe
//
//    #[Route('/stripe/webhook', name: 'app_stripe_web_hook')]
//    public function indexWebhook(Request $request, ProductRepository $productRepository)
//    {
//
//    }

}
