<?php
namespace App\Controller;
use DateTime;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Degree;
use App\Entity\Upload;
use League\Csv\Reader;
use App\DTO\UserSearch;
use App\Entity\Issuance;
use App\Form\UserFormType;
use App\Entity\DegreeTitle;
use App\Entity\SmartUpload;
use App\Entity\GraduateUser;
use App\Form\RefusalFormType;
use App\Form\FileUploadFormType;
use App\Entity\GraduateUserInvite;
use App\DTO\GraduateUserInviteSearch;
use App\Form\GraduateUserInviteFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class IssuanceController extends AbstractController
{
    /**
     * @Route("/issuance", name="issuance")
     */
    public function class(Issuance $issuance, Request $request, \Swift_Mailer $mailer)
    {
        /** 
         * Get User
         */
        $user = $this->getUser();
        if($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute("issuances");
            }
            $graduateUserInvite = $this->graduateUserInvite($request,$mailer, $issuance);
            $fileUploadHandler = $this->fileUploadHandler($request, $issuance);
        return $this->render(
            'Default/Issuances/issuance.html.twig',
            [
                'user' => $user,
                'issuance' => $issuance,
                'graduateUserInviteForm' => $graduateUserInvite[0]->createView(),
                'invitations' => $graduateUserInvite[1],
                'activities' => $graduateUserInvite[2],
                
                'ratio' => $graduateUserInvite[3],
                'nbrI' => $graduateUserInvite[4],
                'nbrA' => $graduateUserInvite[5],
                'nbrD' => $graduateUserInvite[6],
                'nbrN' => $graduateUserInvite[7],
                'pendings' => $graduateUserInvite[8],
                'users' => $graduateUserInvite[9],
                'uploadForm' => $fileUploadHandler[0]->createView(),
                'uploadedFile' => $fileUploadHandler[1],
                'user' => $fileUploadHandler[2],
                'smartUploadedFile' => $fileUploadHandler[3],
             
            ]
        );
    }
    public function graduateUserInvite(Request $request, \Swift_Mailer $mailer, Issuance $issuance) {
        /** 
         * Get School of SchoolUser inviting
         */
        $school = $this->getUser()->getSchoolUser()->getSchool();
        
        /** 
         * Instantiate invitation & set issuance"
         */
         
        $graduateUserInvite = new GraduateUserInvite();
        
        $form = $this->createForm(GraduateUserInviteFormType::class, $graduateUserInvite, [
            'standalone' => true,
            'method' => 'POST'
            ]);
        $form->handleRequest($request);
         
        /** 
         * If file uploaded
         */
        if(!empty($issuance->getSmartFile())){
            $excel = $issuance->getSmartFile()->getExcel();
        
            /**
             * Reader path
             */
            $reader = Reader::createFromPath(__DIR__.'/../../public/uploads/issuances/excels/'.$excel, 'r');
            $reader->setHeaderOffset(0);
            $records = $reader->getRecords(['firstname','lastname','email','code','hono']);
            foreach($records as $offset => $record){
                $row = explode(';', $record['firstname']);
                /**
                 * Get smartUpload object
                 */
                $smartUpload = $this->getDoctrine()->getManager()->getRepository(SmartUpload::class)->findOneByIssuance($issuance);
                /**
                 * $row[2] is email column, verify if email not already registered
                 */
                $email_compare2 = new UserSearch();
                $email_compare2->setSearch($row[2]);
                $findUser = $this->getDoctrine()->getManager()->getRepository(User::class)->findByEmail($email_compare2);
                $email_compare = new GraduateUserInviteSearch();
                $email_compare->setSearch($row[2]);
                $findGraduateUserInvite = $this->getDoctrine()->getManager()->getRepository(GraduateUserInvite::class)->findByEmail($email_compare);
                
                if ($form->isSubmitted() && $form->isValid()) {
               
                    if(empty($findGraduateUserInvite) && empty($findUser)) {
                        /**
                         * Instantiate invitation
                         */
                        $random = random_int(100001, 1999999999999);
                        $graduateUserInvite = new GraduateUserInvite();
                        $graduateUserInvite->addIssuance($issuance);
                        $graduateUserInvite->setFirstname($row[0]);
                        $graduateUserInvite->setLastname($row[1]);
                        $graduateUserInvite->setEmail($row[2]);
                        $graduateUserInvite->setHash($random);
                        $graduateUserInvite->setSchool($school);
                        /**
                         * Instantiate a degree
                         */
                        $degree = $this->createDegree($row, $smartUpload);
                        $issuance->addDegree($degree[0]);
                        $graduateUserInvite->addDegree($degree[0]);
                        /**
                        * Flush new instantiated invitation
                        */
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($graduateUserInvite);
                        $entityManager->flush();
                        /**
                         * Instantiate invitation email for the first time
                         */
                        
                        $message = (new \Swift_Message('Welcome'))
                            ->setFrom("support@nimble.degree")
                            ->setTo($row[2])
                            ->setBody(
                                $this->renderView(
                                    'Email/graduateUserInvite.html.twig',
                                    [
                                        "graduateUserInvite" => $graduateUserInvite,
                                        "school" => $school
                                    ]
                                ),
                                'text/html'
                            )
                            ->addPart(
                                $this->renderView(
                                    'Email/graduateUserInvite.txt.twig',
                                    [
                                        "graduateUserInvite" => $graduateUserInvite,
                                        "school" => $school
                                    ]
                                ),
                                'text/plain'
                            );
                        $mailer->send($message);
                    
                        continue;
                    /**
                     * If only schoolUser or corporateUse & invited but anwered or not answered (hence no grad)
                     */
                    }elseif(!empty($findGraduateUserInvite) && !empty($findUser)) {      
                        foreach($findGraduateUserInvite as $invitation){
                            /**
                             * Instantiate a degree
                             */
                            $degree = $this->createDegree($row, $smartUpload);
                            $issuance->addDegree($degree[0]);
                            /**
                             * Update invitation
                             */
                            $invitation->addDegree($degree[0]);
                            $invitation->addIssuance($issuance);
                            
                            $acceptance = $invitation->getAcceptance();
                            if($acceptance == 'accepted'){
            
                                /**
                                 * Instantiate invitation email for n time
                                 */
                                $message = (new \Swift_Message('Welcome'))
                                    ->setFrom("support@nimble.degree")
                                    ->setTo($row[2])
                                    ->setBody(
                                        $this->renderView(
                                            'Email/newProofOfDegree.html.twig',
                                            [
                                                "graduateUserInvite" => $invitation,
                                                "school" => $school
                                            ]
                                        ),
                                        'text/html'
                                    )
                                    ->addPart(
                                        $this->renderView(
                                            'Email/newProofOfDegree.txt.twig',
                                            [
                                                "graduateUserInvite" => $invitation,
                                                "school" => $school
                                            ]
                                        ),
                                        'text/plain'
                                    );
                                $mailer->send($message);
                            }else{
                                /**
                                 * Instantiate invitation email for n time
                                 */
                                
                                $message = (new \Swift_Message('Welcome'))
                                ->setFrom("support@nimble.degree")
                                ->setTo($row[2])
                                ->setBody(
                                    $this->renderView(
                                        'Email/graduateUserInviteAdd.html.twig',
                                        [
                                            "graduateUserInvite" => $invitation,
                                            "school" => $school
                                        ]
                                    ),
                                    'text/html'
                                )
                                ->addPart(
                                    $this->renderView(
                                        'Email/graduateUserInviteAdd.txt.twig',
                                        [
                                            "graduateUserInvite" => $invitation,
                                            "school" => $school
                                        ]
                                    ),
                                    'text/plain'
                                );
                            $mailer->send($message);
                            }
                        }
                        foreach($findUser as $user){
                            $graduateUser = $user->getGraduateUser();
                            /**
                             * If answered (graduateUser)
                             */
                            if(!empty($graduateUser)){
                                /**
                                 * Update GraduateUser
                                 */
                                $school = $issuance->getSchool();
                
                                $graduateUser->addSchool($school);
                                $graduateUser->addIssuance($issuance);
                                $graduateUser->addDegree($degree[0]);
                            }
                        }
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($invitation);
                        $entityManager->flush();
                        continue;
                    /**
                     * If only schoolUser or corporateUse
                     */
                    }elseif(!empty($findUser) && empty($findGraduateUserInvite)){
                        /**
                        * Instantiate invitation
                        */
                        
                        $graduateUserInvite = new GraduateUserInvite();
                        $random = random_int(100001, 1999999999999);
                        $graduateUserInvite->addIssuance($issuance);
                        $graduateUserInvite->setFirstname($row[0]);
                        $graduateUserInvite->setLastname($row[1]);
                        $graduateUserInvite->setEmail($row[2]);
                        $graduateUserInvite->setHash($random);
                        $graduateUserInvite->setSchool($school);
                        /**
                         * Instantiate degree
                         */
                        $degree = $this->createDegree($row, $smartUpload);
                        $issuance->addDegree($degree[0]);
                        /**
                        * Update invitation
                        */
                        $graduateUserInvite->addDegree($degree[0]);
                        
                        /**
                        * Flush new instantiated invitation
                        */
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($graduateUserInvite);
                        $entityManager->flush();
                        
                        /**
                         * Instantiate invitation email for n time
                         */
                         //to be change by adding directly user
                        
                        $message = (new \Swift_Message('Welcome'))
                            ->setFrom("support@nimble.degree")
                            ->setTo($row[2])
                            ->setBody(
                                $this->renderView(
                                    'Email/graduateUserInvite.html.twig',
                                    [
                                        "graduateUserInvite" => $graduateUserInvite,
                                        "school" => $school
                                    ]
                                ),
                                'text/html'
                            )
                            ->addPart(
                                $this->renderView(
                                    'Email/graduateUserInvite.txt.twig',
                                    [
                                        "graduateUserInvite" => $graduateUserInvite,
                                        "school" => $school
                                    ]
                                ),
                                'text/plain'
                            );
                        $mailer->send($message);
                    
                        continue;
                    /**
                     * If invited but not responded yet
                     */
                    }elseif(!empty($findGraduateUserInvite) && empty($findUser)){
                        
                        foreach($findGraduateUserInvite as $invitation){
                            /**
                             * Instantiate a degree
                             */
                            $degree = $this->createDegree($row, $smartUpload);
                            $issuance->addDegree($degree[0]);
                            /**
                             * Update invitation
                             */
                            $invitation->addDegree($degree[0]);
                            $invitation->addIssuance($issuance);
                            /**
                             * Instantiate invitation email for n time
                             */
                            $message = (new \Swift_Message('Welcome'))
                                ->setFrom("support@nimble.degree")
                                ->setTo($row[2])
                                ->setBody(
                                    $this->renderView(
                                        'Email/graduateUserInviteAdd.html.twig',
                                        [
                                            "graduateUserInvite" => $invitation,
                                            "school" => $school
                                        ]
                                    ),
                                    'text/html'
                                )
                                ->addPart(
                                    $this->renderView(
                                        'Email/graduateUserInviteAdd.txt.twig',
                                        [
                                            "graduateUserInvite" => $invitation,
                                            "school" => $school
                                        ]
                                    ),
                                    'text/plain'
                                );
                            $mailer->send($message);
                        }
                    }
                
                }
            }
        }
        
        $invitations = $this->getDoctrine()->getManager()->getRepository(GraduateUserInvite::class)->findAllByJoinedToIssuance($issuance);
        $activities = $this->getDoctrine()->getManager()->getRepository(GraduateUserInvite::class)->findAnsweredByJoinedToIssuance($issuance);
        $accepted = $this->getDoctrine()->getManager()->getRepository(GraduateUserInvite::class)->findAcceptedByJoinedToIssuance($issuance);
        $null = $this->getDoctrine()->getManager()->getRepository(GraduateUserInvite::class)->findNullByJoinedToIssuance($issuance);
        $declined = $this->getDoctrine()->getManager()->getRepository(GraduateUserInvite::class)->findDeclinedByJoinedToIssuance($issuance);
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        $nbrInvitations = count($invitations);
        $nbrAccepted = count($accepted);
        $nbrNull = count($null);
        $nbrDeclined = count($declined);
        $nbrActivities = count($activities);
        if(!empty($invitations)){
        $ratio = $nbrAccepted/$nbrInvitations;
        $issuance->setProgress($ratio);
        }else{
        $ratio = 0;
        $issuance->setProgress($ratio);
        }
         
        // $registrations = $this->getDoctrine()->getManager()->getRepository(GraduateUser::class)->findByIssuance($issuance);
        return [
            $form,
            $invitations,
            $activities,
            $ratio,
            $nbrInvitations,
            $nbrAccepted,
            $nbrDeclined,
            $nbrNull,
            $null,
            $user
        ];
    }

    // public function deleteInvite(GraduateUserInvite $graduateUserInvite,Request $request) {

    //     $entityManager = $this->getDoctrine()->getManager();
    //     $entityManager->remove($graduateUserInvite);
    //     $entityManager->flush();

    //     return $this->redirectToRoute("issuances");  

    // }

    public function createDegree($row, SmartUpload $smartUpload){
        $user = $this->getUser();
        $schoolUser = $user->getSchoolUser();
        $school = $schoolUser->getSchool();
        $degreeTitle = $this->getDoctrine()->getManager()->getRepository(DegreeTitle::class)->findOneBySchoolAndDegreeCode($school,$row[3]);
        $degree = new Degree();
        
        $degree->setFirstname($row[0]);
        $degree->setLastname($row[1]);
        $degree->setDegreeTitle($degreeTitle[0]);
        $degree->setClassYearStart($smartUpload->getClassYearStart());
        $degree->setClassYearEnd($smartUpload->getClassYearEnd());
        $degree->setHonor($row[4]);
        $degree->setAuthor($schoolUser);
        $degree->setSchool($school);

        return [
            $degree
        ];
    }
    public function graduateUserSignup(GraduateUserInvite $graduateUserInvite, Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {
        
        /**
         * Get issuance
         */
        $issuance = $graduateUserInvite->getIssuance();
        /**
         * Get repositories
         */
    
    
        $graduateUserInviteRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(GraduateUserInvite::class);
        
    
        /**
         * Check if email input already exists
         */
            
        $inviteCompare = $graduateUserInviteRepository->find($graduateUserInvite->getId());
        
            /**
             * Get firstname, lastname & email
             */
            $newEmail = $inviteCompare->getEmail();
            $firstname = $inviteCompare->getFirstname();
            $lastname = $inviteCompare->getLastname();
    
            if($inviteCompare){
            
                $user = new User();
                /**
                 * Set firstname, lastname & email
                 */
    
                $user->setEmail($newEmail);
                $user->setFirstname($firstname);
                $user->setLastname($lastname);
                
                $form = $this->createForm(UserFormType::class, $user, ['standalone' => true]);
                $form->handleRequest($request);
                
                // $term = $form["username"]->getData();
    
                // $SchoolUserSearch = new SchoolUserSearch();
                // $SchoolUserSearch->setSearch($term);
    
                // $manager = $this->getDoctrine()->getManager();
                // $findUser = $this->getDoctrine()->getManager()->getRepository(SchoolUser::class)->findByUsername($SchoolUserSearch);
                
    
                // if(empty($findUser)) {
    
                    if ($form->isSubmitted() && $form->isValid()) {
    
                        $roleRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository(Role::class);
    
                        $role = $roleRepository->find('701b6922-f176-11e8-b65d-8f22fd8dcb51');
    
                        $user->setRoles($role);
                        /**
                         * Update invitation
                         */
                        $currenDateTime = new \DateTime('now');
                        $graduateUserInvite->setAcceptanceDate($currenDateTime);
                        $graduateUserInvite->setAcceptance('accepted');
    
                        /**
                         * Instantiate graduateUser
                         */
    
                        $graduateUser = new GraduateUser();
    
                        $school = $graduateUserInvite->getSchool();
                        $issuances = $graduateUserInvite->getIssuance();
                        foreach($issuances as $issuance){
                            $issuance->addGraduateUser($graduateUser);
                            $faculty= $issuance->getFaculty();
                            $faculty->addGraduateUser($graduateUser);
                        }
                        $degrees = $graduateUserInvite->getDegrees();
                        foreach($degrees as $degree){
                            $graduateUser->addDegree($degree);
                        }
    
                        $graduateUser->addSchool($school);
                        // $graduateUser->addIssuance($issuancesAssociated);
    
                        $user->setGraduateUser($graduateUser);
                            
                        $email = $user->getEmail();
    
                        $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                        $user->setPassword($password);
    
                        $user->setPicture("default.png");
    
                        
                        $message = (new \Swift_Message('Hello Email'))
                        ->setFrom("support@inh.com")
                        ->setTo($email)
                        ->setBody(
                            $this->renderView(
                                'Email/registration.html.twig',
                                array('name' => $firstname)
                            ),
                            'text/html'
                            )
                            
                            ->addPart(
                                $this->renderView(
                                    'Email/registration.txt.twig',
                                    array('name' => $firstname)
                                ),
                                'text/plain'
                            );
                            
                            $mailer->send($message);
                            
                            # add SchoolUser to dtb
                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($user);
                            $entityManager->flush();
                            $inviteCompare->setAcceptance(true);
                            # remove invite
                            // $entityManager->remove($inviteCompare);
                            // $entityManager->flush();
                            
                            return $this->redirectToRoute('login');
                    }
                // }
    
                return $this->render(
                    'Default/graduateUserSignup.html.twig',
                    array('graduateUserForm' => $form->createView()));
            }
                return $this->render(
                'Default/graduateUserSignup.html.twig',
                array('graduateUserForm' => $form->createView())
            );
            
        }
    public function registrationRefusal(GraduateUserInvite $graduateUserInvite, Request $request, \Swift_Mailer $mailer){
    $form = $this->createForm(RefusalFormType::class, $graduateUserInvite);
    $currenDateTime = new \DateTime('now');
    $graduateUserInvite->setAcceptanceDate($currenDateTime);
        
    $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($graduateUserInvite);
            $entityManager->flush();
            $decision = $graduateUserInvite->getAcceptance();
            if ($decision = true ){
                return $this->redirectToRoute("index");
            }else{
                $email = $graduateUserInvite->getEmail();
                // $message = (new \Swift_Message('Hello Email'))
                //         ->setFrom("support@inh.com")
                //         ->setTo($email)
                //         ->setBody(
                //             $this->renderView(
                //                 'Email/refusal.html.twig',
                //                 array('name' => $firstname)
                //             ),
                //             'text/html'
                //             )
                            
                //             ->addPart(
                //                 $this->renderView(
                //                     'Email/refusal.txt.twig',
                //                     array('name' => $firstname)
                //                 ),
                //                 'text/plain'
                //             );
                            
                //             $mailer->send($message);
                return $this->redirectToRoute("index");
            }
            
        }
        return $this->render(
            'Default/registrationRefusal.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
    /**
     * @Route("/fileuploadhandler", name="fileuploadhandler")
     */
    public function fileUploadHandler(Request $request, Issuance $issuance) {
        $user = $this->getUser();
        $schoolUser = $user->getSchoolUser();
        $upload = new Upload();
        $uploadForm = $this->createForm(FileUploadFormType::class, $upload);
        $upload->setIssuance($issuance);
        $upload->setAuthor($schoolUser);
        $uploadForm->handleRequest($request);
        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {
                $file = $uploadForm["excelFile"]->getData();
                $filename = $this->generateUniqueFileName() . "." . $file->guessExtension();
                // $path = $file->move(
                //     $this->getParameter('issuances_directory'),
                //     $filename
                // );
                $upload->setExcel($filename);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($upload);
                $entityManager->flush();
        }
        $uploads = $this->getDoctrine()->getManager()->getRepository(Upload::class)->findOneByIssuance($issuance);
        $smartUploads = $this->getDoctrine()->getManager()->getRepository(SmartUpload::class)->findOneByIssuance($issuance);
        return [
            $uploadForm,
            $uploads,
            $user,
            $smartUploads,
    
        ]; 
        // $output = array('uploaded' => false);
        // // get the file from the request object
        // $file = $request->files->get('file');
        
        // // generate a new filename (safer, better approach)
        // // To use original filename, $fileName = $this->file->getClientOriginalName();
        // $fileName = md5(uniqid()).'.'.$file->guessExtension();
    
        // // set your uploads directory
        // $uploadDir = $this->get('kernel')->getRootDir() . '/../web/uploads/';
        // if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
        //     mkdir($uploadDir, 0775, true);
        // }
        // if ($file->move($uploadDir, $fileName)) { 
        // $output['uploaded'] = true;
        // $output['fileName'] = $fileName;
        // }
        // return new JsonResponse($output);
    }
  
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
    
    
}
