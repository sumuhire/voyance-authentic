<?php
namespace App\Controller;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller{

    public function dashboard(Request $request){
        /** 
         * Get User
         */
        $user = $this->getUser();

        if($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
        return $this->redirectToRoute("login");
        }
    
        return $this->render(
        'Default/dashboard.html.twig'
        );
    }
    public function index(){
        return $this->render(
            'Default/index.html.twig'
        );
    }

    public function login(AuthenticationUtils $authUtils) {
        $error = $authUtils->getLastAuthenticationError();
        
        $lastUsername = $authUtils->getLastUsername();
        return $this->render(
            'Default/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }

    
}
?>