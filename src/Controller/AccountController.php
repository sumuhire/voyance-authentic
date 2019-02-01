<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Degree;
use App\DTO\UserSearch;
use App\Entity\Issuance;
use App\Entity\SchoolUser;
use App\Form\PasswordFormType;

use App\Form\UserSettingsFormType;
use App\Form\ProfilePictureFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends Controller {

    public function editAccount(Request $request, UserInterface $user, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {

        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("education");
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = $this->getUser();
        $picture = $user->getPicture();

        $form = $this->createForm(UserSettingsFormType::class, $user, ['standalone' => false]);
        $form["picture"]->setData($picture);
        $form->handleRequest($request);

        $profileForm = $this->changePicture($request);

        $user = $this->getUser();

        $passwordForm = $this->createForm(PasswordFormType::class, $user, ['standalone' => true]);
        $passwordForm->handleRequest($request);
        
        ## check email availability
        $userSearch = new UserSearch();
        $term = $form["email"]->getData();
        
        $findUser;
        if($term != $user->getEmail()) {

            $userSearch->setSearch($term);
            $findUser = $this->getDoctrine()->getManager()->getRepository(User::class)->findByemail($userSearch);
        }

        if (empty($findUser)) {
            
            if($profileForm->isSubmitted() && $profileForm->isValid()) {

                $file = $profileForm["picture"]->getData();
                $filename = $this->generateUniqueFileName() . "." . $file->guessExtension();

                $path = $file->move(
                    $this->getParameter('picture_directory'),
                    $filename
                );

                $user->setPicture($filename);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }

            if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

                $password = $passwordEncoder->encodePassword($user, $passwordForm["password"]->getData());
                $user->setPassword($password);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

            }  

            if ($form->isSubmitted() && $form->isValid()) {              
                

                #get password
                $password = $user->getPassword();
                $user->setPassword($password);
                
                #get email and name to form a proper email
                $email = $user->getEmail();
                $name = $user->getFirstname();
                
                #send email to notfify about the account changes
                $message = (new \Swift_Message('Account Preferneces Changed'))
                ->setFrom("support@inh.com")
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'Email/Account/accountSettings.html.twig',
                        array('name' => $name)
                    ),
                    'text/html'
                )

                ->addPart(
                    $this->renderView(
                        'Email/Account/accountSettings.html.twig',
                        array('name' => $name)
                    ),
                    'text/plain'
                );
                $mailer->send($message);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            }
        }
           

        return new Response($this->render("User/account_settings.html.twig", ["settings" => $form->createView(), "picture" => $profileForm->createView(), "password" => $passwordForm->createView(), "user" => $user]));
    }

    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder) {

        

    }

    public function changePicture(Request $request) {

        $user = $this->getUser();

        $form = $this->createForm(ProfilePictureFormType::class, $user, ['standalone' => true]);
        $form->handleRequest($request);
        
        return $form;
    }

    public function deleteAccount(Request $request, UserInterface $user) {

        $user = $this->getUser();
        $user->getPassword();
        
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($user);
        $manager->flush();

        return new Response($this->redirectToRoute("login"));
    }

    public function displayAccount(Request $request, UserInterface $user) {

        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("education");
        }

        $user = $this->getUser();

        $graduateUser = $user->getGraduateUser();

        if(!empty($user->getSchoolUser())){

            $schoolOfUser = $user->getSchoolUser()->getSchool();

        }else{

            $schoolOfUser = "";
        }

        if(!empty($graduateUser)){

            $degrees =$this->getDoctrine()->getManager()->getRepository(Degree::class)->findByGradAndYearEndOrder($graduateUser);

        }else{

            $degrees = '';

        }

        return new Response(
            $this->renderView(
                "User/profile.html.twig", 
                [
                    "user" => $user,
                    "degrees" => $degrees,
                    "schoolOfUser" => $schoolOfUser
                    
                ]
            )
        );
    }

    public function visitAccount(Request $request, User $user)
    {   
        $user2 = $this->getUser();
        

        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("education");
        }
        
        if($user2 != $user ){

            ## $findUser = ->getRepository(User::class)->findByEmail($term);
            $query =    $this->getDoctrine()->getManager()->createQuery(
                        "SELECT u
                        FROM App\Entity\User u
                        WHERE u.id = :id")
                        ->setParameter("id", $user);

            $findUser = $query->execute();

            $graduateUser = $user->getGraduateUser();

            $issuances =$this->getDoctrine()->getManager()->getRepository(Issuance::class)->findByGrad($graduateUser);

            $degrees =$this->getDoctrine()->getManager()->getRepository(Degree::class)->findByGradAndYearEndOrder($graduateUser);
            
            return new Response(
                $this->renderView(
                    "User/visiting_profile.html.twig", 
                    [
                        "visitedUser" => $user,
                        "user" => $user2,
                        "degrees" => $degrees
                    ]
                )
            );
        }

        
        return new Response($this->redirectToRoute("profile"));
       

        
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

}

?>