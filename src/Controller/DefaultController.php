<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller{

    public function index(){

        return $this->render(
            'Default/index.html.twig'
        );
    }


    public function projectsMenu(){

        return $this->render(
            'Projects/projectsMenu.html.twig'
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


    public function project(){

        return $this->render(
            'Projects/project.html.twig'
        );
    }

}
?>