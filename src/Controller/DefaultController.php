<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller{

    public function index(){

        return $this->render(
            'Default/index.html.twig'
        );
    }

    public function pressMenu(){

        return $this->render(
            'Press/pressMenu.html.twig'
        );
    }

    public function about(){

        return $this->render(
            'Default/about.html.twig'
        );
    }


    public function produit(){

        return $this->render(
            'Produits/produit.html.twig'
        );
    }

    public function dossier(){

        return $this->render(
            'Dossier/dossier.html.twig'
        );
    }

    public function dossiers(){

        return $this->render(
            'Dossier/dossiers.html.twig'
        );
    }

    public function panier(){

        return $this->render(
            'Panier/panier.html.twig'
        );
    }

    public function inscription(){

        return $this->render(
            'Default/inscription.html.twig'
        );
    }

    public function commande(){

        return $this->render(
            'Panier/commande.html.twig'
        );
    }


}
?>