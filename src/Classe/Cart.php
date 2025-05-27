<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {

    }

/*
 * add()
 * fonction permettant l'ajout d'un produit au panier
 */
    public function add($product)
    {
        //Récupération de la session de Symfony
        $session = $this->requestStack->getSession();

        $cart = $this->getCart();
        //Ajouter une qty +1 à mon produit
        if(isset($cart[$product->getId()])){
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        }else{
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }
        $session->set('cart', $cart);
    }

    /*
     * decrease()
     * fonction permettant la suppression d'un produit au panier
     */
    public function decrease($id)
    {
        //Récupération de la session de Symfony
        $session = $this->requestStack->getSession();

        $cart = $this->getCart();
        //Ajouter une qty +1 à mon produit
        if($cart[$id]['qty'] > 1){
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        }else
        {
           unset($cart[$id]);
        }
        $session->set('cart', $cart);
    }

    /*
     * fullQuantity()
     * fonction retournant le nombre total de produit au panier
     */
    public function fullQuantity(){
        $cart = $this->getCart();
        $quantity = 0;
        if(!isset($cart)){
            return 0;
        }
        foreach ($cart as $product){
            $quantity = $quantity + $product['qty'];
        }
        return $quantity;
    }

    /*
     * getTotalWt()
     * fonction retournant le prix total du panier
     */
    public function getTotalWt(){
        $session = $this->requestStack->getSession();
        $cart = $this->getCart();
        $totalWt = 0;
        if(!isset($cart)){
            return 0;
        }
        foreach ($cart as $product){
            $totalWt = $totalWt + ($product['qty'] * $product['object']->getPriceWt());
        }
        return $totalWt;
    }

    /*
     * remove()
     * fonction supprimant la contenu du panier
     */
    public function remove(){
        return $this->requestStack->getSession()->remove('cart');
    }

    /*
 * getCart()
 * fonction permettant la récupération du panier
 */
    public function getCart(){
        return $this->requestStack->getSession()->get('cart');
    }


}