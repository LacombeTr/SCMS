<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;

class cartService
{

    public function priceChecker(array $cart, ProductRepository $productRepository): array
    {
        // Structure d'un cart:
        //  {
        //       id_produit: {"id": integer, "name": string, "price": float, "quantity": integer},
        //       [...]
        //  }

        // Rencupère les données de la DB, sécurité pour le prix par exemple
        foreach ($cart as $key => $value) {
            // Récupération des prix dans la database pour s'assurer que ce sont les prix réels et pas ceux modifiés par l'utilisateur
            $price = $productRepository->findOneBy(['id' => $key])->getPrice() * ($value["quantity"]);
            $value["price"] = $price;
        }

        // Renvoie un tableau
        return $cart;
    }
}
