<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller{

    public function index(){

        return $this->render(
            'Default/index.html.twig'
        );
    }

    public function reviews(){

        return $this->render(
            'Reviews/reviews.html.twig'
        );
    }



}
?>