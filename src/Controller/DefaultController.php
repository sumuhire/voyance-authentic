<?php
namespace App\Controller;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Antipasti;
use App\Entity\Primi;
use App\Entity\Secondi;
use App\Entity\Dolci;
use App\Entity\Formaggio;
use App\Form\AntipastiFormType;
use App\Form\PrimiFormType;
use App\Form\SecondiFormType;
use App\Form\DolciFormType;
use App\Form\FormaggioFormType;
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
        if ($piatto[0]->isSubmitted() && $piatto[0]->isValid()) {

            return $this->redirectToRoute('dashboard');

        }
        
        /** 
         * Get AntipastiForm & Antipasti alla carta
         */
        $antipasti = $this->antipasti($request);
        if ($antipasti[0]->isSubmitted() && $antipasti[0]->isValid()) {

            return $this->redirectToRoute('dashboard');

        }
        /** 
         * Get PrimiForm & Primi alla carta
         */
        $primi = $this->primi($request);
        if ($primi[0]->isSubmitted() && $primi[0]->isValid()) {

            return $this->redirectToRoute('dashboard');

        }
        /** 
         * Get SecondiForm & Secondi alla carta
         */
        $secondi = $this->secondi($request);
        if ($secondi[0]->isSubmitted() && $secondi[0]->isValid()) {

            return $this->redirectToRoute('dashboard');

        }
        /** 
         * Get DolciForm & Dolci alla carta
         */
        $dolci = $this->dolci($request);
        if ($dolci[0]->isSubmitted() && $dolci[0]->isValid()) {

            return $this->redirectToRoute('dashboard');

        }
        /** 
         * Get FormaggioForm & Formaggio alla carta
         */
        $formaggio = $this->formaggio($request);
        
 
    
        return $this->render(
        'Default/dashboard.html.twig',
         
            [
                'piattoForm' => $piatto[0]->createView(),
                'antipastiForm' => $antipasti[0]->createView(),
                'antipasti' => $antipasti[1],
                'primiForm' => $primi[0]->createView(),
                'primi' => $primi[1],
                'secondiForm' => $secondi[0]->createView(),
                'secondi' => $secondi[1],
                'dolciForm' => $dolci[0]->createView(),
                'dolci' => $dolci[1],
                'formaggioForm' => $formaggio[0]->createView(),
                'formaggio' => $formaggio[1],
               
                
            ]
        );
    }

    public function index(Request $request){

        /** 
         * Get Menu
         */
        $antipasti = $this->antipasti($request);
        $primi = $this->primi($request);   
        $secondi = $this->secondi($request);
        $dolci = $this->dolci($request);
        $formaggio = $this->formaggio($request);

        return $this->render(
            'Default/index.html.twig',
         
            [
    
                'antipasti' => $antipasti[1],
                'primi' => $primi[1],
                'secondi' => $secondi[1],
                'dolci' => $dolci[1],
                'formaggio' => $formaggio[1],
               
                
            ]
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

    public function primi(Request $request){

        $primi = $this->getDoctrine()->getManager()->getRepository(Primi::class)->findOneById(1);

        $primiForm = $this->createForm(PrimiFormType::class, $primi, ['standalone' => true]);

        $primiForm->handleRequest($request);

        if ($primiForm->isSubmitted() && $primiForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($primi);
            $entityManager->flush();

            

        };

        $premier= $this->getDoctrine()->getManager()->getRepository(Primi::class)->findOneById(1);

        return [
                
                $primiForm,
                $premier

            ];

    }

    public function secondi(Request $request){

        $secondi = $this->getDoctrine()->getManager()->getRepository(Secondi::class)->findOneById(1);

        $secondiForm = $this->createForm(SecondiFormType::class, $secondi, ['standalone' => true]);

        $secondiForm->handleRequest($request);

        if ($secondiForm->isSubmitted() && $secondiForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($secondi);
            $entityManager->flush();

            

        };

        $second= $this->getDoctrine()->getManager()->getRepository(Secondi::class)->findOneById(1);

        return [
                
                $secondiForm,
                $second

            ];

    }

    public function dolci(Request $request){

        $dolci = $this->getDoctrine()->getManager()->getRepository(Dolci::class)->findOneById(1);

        $dolciForm = $this->createForm(DolciFormType::class, $dolci, ['standalone' => true]);

        $dolciForm->handleRequest($request);

        if ($dolciForm->isSubmitted() && $dolciForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dolci);
            $entityManager->flush();

            

        };

        $dessert= $this->getDoctrine()->getManager()->getRepository(Dolci::class)->findOneById(1);

        return [
                
                $dolciForm,
                $dessert

            ];

    }

    public function formaggio(Request $request){

        $formaggio = $this->getDoctrine()->getManager()->getRepository(Formaggio::class)->findOneById(1);

        $formaggioForm = $this->createForm(FormaggioFormType::class, $formaggio, ['standalone' => true]);

        $formaggioForm->handleRequest($request);

        if ($formaggioForm->isSubmitted() && $formaggioForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formaggio);
            $entityManager->flush();

            

        };

        $entrée= $this->getDoctrine()->getManager()->getRepository(Formaggio::class)->findOneById(1);

        return [
                
                $formaggioForm,
                $entrée

            ];

    }

    public function editare(Piatto $piatto, Request $request){ 

        $piattoEditareForm = $this->createForm(PiattoFormType::class, $piatto, 
        [
            'standalone' => true,
        ]);

        $piattoEditareForm->handleRequest($request);

        if ($piattoEditareForm->isSubmitted() && $piattoEditareForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($piatto);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');

        };

        return $this->render(
            'Default/editare.html.twig', [
                
                'piattoEditareForm' => $piattoEditareForm->createView(),

            ]
        );

    }

    
}
?>