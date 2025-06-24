<?php

namespace App\Controller;

use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Attribute\Route;

final class StripeController extends AbstractController
{
    public function __construct(private ParameterBagInterface $params)
    {
    }

    #[Route('/stripe', name: 'app_stripe')]
    public function index()
    {
        $secretKey = $_ENV['STRIPE_PRIVATE_SECRET_KEY'] ?? null;

        if (!$secretKey) {
            throw new \RuntimeException('STRIPE_PRIVATE_SECRET_KEY manquante.');
        }
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        $stripe = new StripeClient($secretKey);
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys

        // $product = $stripe->products->create(['name' => 'Per-seat']);
        // $product = $product->values();
        $price = $stripe->prices->create([
            'currency' => 'eur',
            'unit_amount' => 1000,
            'product_data' => ['name' => 'Gold Plan'],
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
    }
}
