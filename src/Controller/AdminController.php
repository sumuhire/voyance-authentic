<?php
namespace App\Controller;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Invite;


use App\Entity\School;
use App\DTO\UserSearch;
use App\Entity\Faculty;

use App\DTO\InviteSearch;
use App\DTO\SchoolSearch;

use App\Entity\SchoolUser;
use App\Form\InviteFormType;
use App\Form\SchoolFormType;
use App\DTO\SchoolUserSearch;
use App\Form\FacultyFormType;
use App\Form\SchoolLogoFormType;
use App\Form\SchoolSettingFormType;
use App\Form\SchoolUserSearchFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class AdminController extends Controller {

    public function admin(Request $request, \Swift_Mailer $mailer, Userinterface $user, School $school) {

        $userList = $this->userList($request);
        $invite = $this->userInvite($request, $mailer);
        $faculty = $this->facultyList($request, $user);
        // $reports=$this->reportList($request);
        $school = $this->school($request, $school);
        $editSchool = $this->editSchool($request, $user);

        return $this->render(
            'Admin/dashboard.html.twig',
            [
                'users' => $userList[0],
                'searchForm' => $userList[1]->createView(),
                'roleChange' => $userList[2],
                'inviteForm'=> $invite->createView(),
                'schoolForm'=> $school[0]->createView(),
                'schoolUser'=> $school[1],
                'schoolOfUser'=> $school[2],
                'schoolEditForm'=> $editSchool[0]->createView(),
                'schoolLogoForm'=> $editSchool[1]->createView(),
                'ecole'=> $editSchool[2],
                'faculties'=> $faculty[0],
                'facultyForm'=> $faculty[1]->createView(),
                'user'=> $faculty[2]
                // 'school'=> $faculty[2],
                // 'reports' => $reports
                // "warning" => "user or invite already exists"

            ]
        );
    
    }

    public function schoolPage(Request $request, \Swift_Mailer $mailer, Userinterface $user, School $school) {

        $userList = $this->userList($request);
        $invite = $this->userInvite($request, $mailer);
        $faculty = $this->facultyList($request, $user);
        // $reports=$this->reportList($request);
        $school = $this->school($request, $school);
        $editSchool = $this->editSchool($request, $user);

        return $this->render(
            'Admin/schoolPage.html.twig',
            [
                'users' => $userList[0],
                'searchForm' => $userList[1]->createView(),
                'roleChange' => $userList[2],
                'inviteForm'=> $invite->createView(),
                'schoolForm'=> $school[0]->createView(),
                'schoolUser'=> $school[1],
                'schoolOfUser'=> $school[2],
                'schoolEditForm'=> $editSchool[0]->createView(),
                'schoolLogoForm'=> $editSchool[1]->createView(),
                'ecole'=> $editSchool[2],
                'faculties'=> $faculty[0],
                'facultyForm'=> $faculty[1]->createView(),
                'user'=> $faculty[2]
                // 'school'=> $faculty[2],
                // 'reports' => $reports
                // "warning" => "user or invite already exists"

            ]
        );
    
    }

    public function userInvite(Request $request, \Swift_Mailer $mailer) {

        /** 
         * Instantiate invitation & check if inputted email already inputted in the past
         */
         
        $invite = new Invite();

        // $schoolUser = new SchoolUser();

        // $schoolUser->setInvite($invite);

        $form = $this->createForm(InviteFormType::class, $invite, [
            'standalone' => true,
            'method' => 'POST'
            ]);

        $form->handleRequest($request);
        
        $emailList = $form->get("email")->getData();

        $emails = explode(",", $emailList);

        /** 
         * Get School of SchoolUser inviting
         */

        $school = $this->getUser()->getSchoolUser()->getSchool();

        foreach($emails as $email){

            $email_compare2 = new UserSearch();
            $email_compare2->setSearch($email);
            $findUser = $this->getDoctrine()->getManager()->getRepository(User::class)->findByEmail($email_compare2);

            $email_compare = new InviteSearch();
            $email_compare->setSearch($email);
            $findInvite = $this->getDoctrine()->getManager()->getRepository(Invite::class)->findByEmail($email_compare);

            # check if email already exists in Database
            if(empty($findInvite) && empty($findUser)) {

                if ($form->isSubmitted() && $form->isValid()) {

                    $random = random_int(10000, 1999999);
                    $invite = new Invite();
                    $invite->setHash($random);
                    $invite->setEmail($email);
                    $invite->setSchool($school);

                    #$email = $form->get("email")->getData();

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($invite);
                    $entityManager->flush();

                    $message = (new \Swift_Message('Welcome'))
                        ->setFrom("support@nimble.degree")
                        ->setTo($email)
                        ->setBody(
                            $this->renderView(
                                'Email/invite.html.twig',
                                ["invite" => $invite]
                            ),
                            'text/html'
                        )

                        ->addPart(
                            $this->renderView(
                                'Email/invite.txt.twig',
                                ["invite" => $invite]
                            ),
                            'text/plain'
                        );

                    $mailer->send($message);

                }
            }
            # give user information that email exists
        }



        // return new Response($this->renderView(
        //     'Admin/inviteForm.html.twig',
        //     ["inviteForm" => $form->createView(), "warning" => "user or invite already exists"]
        // ));

        return $form;
    }


    public function listInvites() {
        $entityManager = $this->getDoctrine()->getManager();

        /**
         * Get School of SchoolUser inviting
         */ 

        $user = $this->getUser();
        $school = $user->getSchoolUser()->getSchool();


        $invites = $entityManager->getRepository(Invite::class)->findBySchool($school);
        
        return new Response(
            $this->renderView(
                "Admin/Lists/inviteList.html.twig", 
                    [
                        "invites" => $invites,
                        "user" => $user
                        ]
                )
            );

    }


    # make the lastSearch term persist trough the execution
    public function userList(Request $request) {

        $roleChange = $request->get("change");
        
        if(!isset($roleChange)) {
            $roleChange = 2;
        }

        /**
         * Instantiate two schoolUser searchs for firstname & lastname
         */

        $term = new SchoolUserSearch();

        $term2 = new SchoolUserSearch();

        $searchForm = $this->createForm(SchoolUserSearchFormType::class, $term, ['standalone' => true]);
        
        $searchForm->handleRequest($request);
        
        /**
         * Explode in two search input
         */


        $terms = $term->getSearch();
        $termsSplit = explode(" ", $terms);



        /**
         * If there is a second search, search for both schoolUser objects
         */

        if(isset($termsSplit[1])) {
            $term->setSearch($termsSplit[0]);
            $term2->setSearch($termsSplit[1]);
            $lastSearchTerm = $term->getSearch() . " " . $term2->getSearch();
        }
        else {

            /**
             * Search for the first schoolUser search object
             */

            $term->setSearch($terms);
            $lastSearchTerm = $term->getSearch();
        }
        
        /**
         * Get School of schoolUser
         */
        
        $school = $this->getUser()->getSchoolUser()->getSchool();

        /**
         * Find user associated to the same school
         */

        $users = $this->getDoctrine()->getManager()->getRepository(SchoolUser::class)->findBySchool($school);

        

            /**
             * Search if email, if username, if firstname, if lastname or latter both
             * Get data from repository
             */

            if (filter_var($terms, FILTER_VALIDATE_EMAIL)) {
                $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findByEmail($term);
                
            } else if (isset($termsSplit[1])) {
                $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findByName($term, $term2);
                
            } else if(!isset($termsSplit[1])){
                $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findByUsername($term);
                $users += $this->getDoctrine()->getManager()->getRepository(User::class)->findByFirstName($term);
                $users += $this->getDoctrine()->getManager()->getRepository(User::class)->findByLastName($term);
            }
            else  {
                $userRepository = $this->getDoctrine()->getManager()->getRepository(User::class);
                $users = $userRepository->findAll(); 
            }
        
            
            $schoolUsers = $this->getDoctrine()->getManager()->getRepository(SchoolUser::class)->findSchoolUserBySchool($school);
        
        return [
            $schoolUsers,
            $searchForm,
            $roleChange
        ];
         
        
    }


    // public function reportList(Request $request) {

    //     $reports = $this->getDoctrine()->getManager()->getRepository(Report::class)->findAll();

    //     // return new Response($this->render("Admin/Lists/reportList.html.twig", ["reports" => $reports]));

    //     return $reports;
    // }

    public function FacultyList(Request $request, UserInterface $user) {

        //Instantiate a faculty & facultyform instances
        $faculty = new Faculty();

        $facultyForm = $this->createForm(FacultyFormType::class, $faculty);

        //Get & add schoolUser/faculty to school instance
        $schoolUser = $user->getSchoolUser();
        $school = $schoolUser->getSchool();

        $faculty->setSchool($school)->addSchoolUser($schoolUser);;

        $facultyForm->handleRequest($request);
        
        $faculties = $this->getDoctrine()->getManager()->getRepository(Faculty::class)->findBySchool($school);

        //Update only faculties where school is linked to our school

        if ($facultyForm->isSubmitted() && $facultyForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($faculty);
            $entityManager->flush();
        }

        // return new Response($this->render("Admin/Lists/facultyList.html.twig", ["faculties" => $faculties, "d_form" => $facultyForm->createView()]));

        return [
            $faculties,
            $facultyForm,
            $user
        ];
    
    }

    public function deleteFaculty(Faculty $faculty, Request $request) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($faculty);
        $entityManager->flush();

        $school = $user->getSchoolUser()->getSchool()->getId();

        return $this->redirectToRoute("admin", array(" school => $school "));      

    }


    public function school(Request $request, School $ecole) {

        /**
         * Instantiate a school & schoolform instances
         */
        $school = new School();

        $schoolForm = $this->createForm(SchoolFormType::class, $school);

        /**
         * Get & add schoolUser to school instance
         */

        $schoolUser = $this->getUser();
        $user = $this->getUser()->getSchoolUser()->getId();

        $schoolUser = $this->getDoctrine()->getManager()->getRepository(SchoolUser::class)->findOneById($user);


        $school->addSchoolUser($schoolUser);

        /**
         * Add default logo to school instance
         */

        $school->setLogo('default.png');

       
        $schoolForm->handleRequest($request);


        if ($schoolForm->isSubmitted() && $schoolForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($schoolA);
            $entityManager->flush();
        }

        
        // /**
        //  * Get school of SchoolUser
        //  */

        // $schoolOfUser = $request->query->get('school');

        //Find school by school ID of schoolUser
        /**
         * Get & add schoolUser to school instance
         */  
        $findSchool = $this->getDoctrine()->getManager()->getRepository(School::class)->findOneById($ecole);

        return [
            $schoolForm,
            $schoolUser,
            $findSchool
        ];
    
    }

    public function editSchool(Request $request, UserInterface $user) {

        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("education");
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        

        // $schoolOfUser = $this->getDoctrine()->getManager()->getRepository(School::class)->findOneById($school);

        $schoolOfUser = $user->getSchoolUser()->getSchool();

        $schoolLogo = $schoolOfUser->getLogo();

        $schoolSettingForm = $this->createForm(SchoolSettingFormType::class, $schoolOfUser, ['standalone' => false]);
        $schoolSettingForm["logo"]->setData($schoolLogo);
        $schoolSettingForm->handleRequest($request);

        $logoForm = $this->changeLogo($request, $user);
        
        // check name availability
        $schoolSearch = new SchoolSearch();
        $term = $schoolSettingForm["name"]->getData();
        
        $findUser;

        if($term != $schoolOfUser->getName()) {

            $schoolSearch->setSearch($term);
            $findUser = $this->getDoctrine()->getManager()->getRepository(School::class)->findByName($schoolSearch);
        }

        if (empty($findUser)) {
            
            if($logoForm->isSubmitted() && $logoForm->isValid()) {

                // $file stores the uploaded logo

                $file = $logoForm["logo"]->getData();
                $filename = $this->generateUniqueFileName() . "." . $file->guessExtension();

                // moves the file to the directory where logos are stored
                $path = $file->move(

                    $this->getParameter('picture_directory'),
                    $filename

                );
                
                 // updates the 'logo' property to store the logo file name
                $school->setLogo($filename);

                // persist
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($schoolOfUser);
                $entityManager->flush();
            }

            if ($schoolSettingForm->isSubmitted() && $schoolSettingForm->isValid()) {       


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($schoolOfUser);
            $entityManager->flush();
            }
        }
           

        return [
            $schoolSettingForm,
            $logoForm,
            $schoolOfUser
        ];
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    public function changeLogo(Request $request, Userinterface $user) {

        $school = $user->getSchoolUser()->getSchool();

        $form = $this->createForm(SchoolLogoFormType::class, $school, ['standalone' => true]);

        $form->handleRequest($request);
        
        return $form;
    }

    public function makeAdmin(User $user, Request $request) {

        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $role = $roleRepository->find('5f292de8-f176-11e8-b65d-8f22fd8dcb51');

        if($user->getRoles()[0] != $role) {

            $this->flushUser($user, $role);

            $email = $user->getEmail();
            $reason = "Email/Account/admin.html.twig";
            $this->sendMail($reason, $email);

            return $this->redirectToRoute("userList", ["change" => 0]);
        }

        return $this->redirectToRoute("userList", ["change" => 1]);
    }

    // public function makeUser(User $user, Request $request) {

    //     $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
    //     $role = $roleRepository->find(2);

    //     if ($user->getRoles()[0] != $role) {

    //         $this->flushUser($user, $role);

    //         $email = $user->getEmail();
    //         $reason = "Email/Account/noAdmin.html.twig";
    //         $this->sendMail($reason, $email);


    //         return $this->redirectToRoute("userList", ["change" => 3]);
    //     }
    //     return $this->redirectToRoute("userList", ["change" => 1]);
    // }


    // public function makeInactive(User $user, Request $request) {

    //     $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
    //     $inactive = $roleRepository->find(3);
    //     $role = $roleRepository->find(1);

    //     if ($user->getRoles()[0] != $role && $user->getRoles() != $inactive ) {

    //         $this->flushUser($user, $inactive);

    //         $reason = "Email/Account/inactive.html.twig";
    //         $email = $user->getEmail();
    //         $this->sendMail($reason, $email);


    //         return $this->redirectToRoute("userList", ["change" => 4]);
    //     }
    //     return $this->redirectToRoute("userList", ["change" => 1]);
    // }

    public function sendMail(string $reason, string $email) {
        
        $transport = new \Swift_SmtpTransport("localhost:1025");
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('support@inh.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    $reason
                ),
                'text/html'
            )
        ->addPart(
            $this->renderView(
                $reason
            ),
            'text/plain'
        );
        $mailer->send($message);
    }



    public function userDetail(User $user, Request $request) {
        return new Response($this->render("Admin/Lists/userDetail.html.twig", ["user" => $user]));
    }

    

    public function facultyDetails(){

        
    }

    // public function countUserDepartment()
    // {
    //     $user = $this->getDoctrine()->getRepository(User::class);
    //     $department = $this->getDoctrine()->getRepository(Department::class)->FindByDepartment();
    //     $NbrUser =$department->getLabel($department);
      

    //     return $this->render(
    //         'Admin/dashboard.html.twig',
    //         [
    //             'depart'=>$department,
    //             'cound'=>$countUd
    //         ]
    //     );
    // }


    
}


?>
