<?php
namespace App\Controller;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Antipasti;
use App\Form\AntipastiFormType;
use App\Entity\Piatto;
use App\Entity\PiattoType;
use App\Form\PiattoFormType;
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

         /** 
         * Get PiattoForm
         */
        $piatto = $this->piattoForm($request);
        $antipasti = $this->antipasti($request);
 
    
        return $this->render(
        'Default/dashboard.html.twig',
            [
                'piattoForm' => $piatto[0]->createView(),
                'antipastiForm' => $antipasti[0]->createView(),
                'antipasti' => $antipasti[1]
            ]
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

    public function piattoForm(Request $request){

        $piatto = new Piatto();

        $piattoForm = $this->createForm(PiattoFormType::class, $piatto, ['standalone' => true]);

        $piattoForm->handleRequest($request);

        if ($piattoForm->isSubmitted() && $piattoForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($piatto);
            $entityManager->flush();

        };

        return [
                
                $piattoForm

            ];
 

    }

    public function antipasti(Request $request){

        $antipasti = $this->getDoctrine()->getManager()->getRepository(Antipasti::class)->findOneById(1);

        $antipastiForm = $this->createForm(AntipastiFormType::class, $antipasti, ['standalone' => true]);

        $antipastiForm->handleRequest($request);

        if ($antipastiForm->isSubmitted() && $antipastiForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($antipasti);
            $entityManager->flush();

        };

        $entrée= $this->getDoctrine()->getManager()->getRepository(Antipasti::class)->findOneById(1);

        return [
                
                $antipastiForm,
                $entrée

            ];
 

    }

    
}
?>