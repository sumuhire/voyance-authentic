<?php
namespace App\Controller;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Degree;
use App\Entity\Invite;
use App\Entity\School;
use League\Csv\Reader;
use App\DTO\UserSearch;
use App\Entity\Faculty;
use App\Entity\Issuance;
use App\Entity\SchoolUser;
use App\Form\UserFormType;
use App\Entity\DegreeTitle;
use App\Entity\SmartUpload;
use App\Entity\GraduateUser;
use App\DTO\SchoolUserSearch;
use App\Form\IssuanceFormType;
use App\Form\SchoolUserFormType;
use App\Form\SmartUploadFormType;
use App\Entity\GraduateUserInvite;
use App\Form\GraduateUserFormType;
use App\Form\SmartIssuanceFormType;
use App\Form\QuestionSearchFormType;
use App\DTO\GraduateUserInviteSearch;
use App\Form\GraduateUserInviteFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends Controller{

    public function education(Request $request){
        /** 
         * Get User
         */
        $user = $this->getUser();

        if($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
        return $this->redirectToRoute("login");
        }
        /** 
         * Function call to render in education
         */
        
        $issuance = $this->issue($request);
        $upload = $this->SmartfileUploadHandler($request);
       
        return $this->render(
        'Default/education.html.twig',
            array(
                "user" => $user,
                'issuanceForm' => $issuance[0]->createView(),
                'issuancess' => $issuance[1],
                'graduates' => $issuance[2],
                'faculties' => $issuance[3],
                'school' => $issuance[4],
                'smartUploadForm' => $upload[0]->createView(),

            )
        );
    }
    public function index(){
        return $this->render(
            'Default/index.html.twig'
        );
    }
    public function about(){
        return $this->render(
            'Default/about.html.twig'
        );
    }
    public function faq(){
        return $this->render(
            'Default/faq.html.twig'
        );
    }
    public function signup(Invite $invite, Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {
    /**
     * Get repository
     */
    $inviteRepository = $this->getDoctrine()->getManager()->getRepository(Invite::class);
    /**
     * Get schoolUser
     */
    $currentUser = $this->getUser();
    $schoolUser = $currentUser->getSchoolUser();
    $school = $schoolUser->getSchool();
    $schoolPicture= $school->getLogo();     
        /**
         * Check if email input already exists
         */
            
        $inviteCompare = $inviteRepository->find($invite->getId());
        $newEmail = $inviteCompare->getEmail();
        if($inviteCompare){
            /**
             * Instantiate user & schoolUser
             */
            $user = new User();
            $user->setPicture($schoolPicture);
            $user->setEmail($newEmail);
            $schoolUser = new SchoolUser();
            $schoolUser->setSchool($school);
           

            /**
             * Set role
             */
            $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
            
            $role = $roleRepository->find('701b6922-f176-11e8-b65d-8f22fd8dcb51');
            $user->setRoles($role);
            /**
             * Form instantation
             */
            $userForm = $this->createForm(SchoolUserFormType::class, $schoolUser, ['standalone' => true]);
            $userForm->handleRequest($request);
            
                if ($userForm->isSubmitted() && $userForm->isValid()) {
                     
                /**
                 * Set Password
                 */
                // $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                // $user->setPassword($password);
                    $name = $user->getFirstname();
                    $email = $user->getEmail();
                       
                    $message = (new \Swift_Message('Welcome'))
                    ->setFrom("support@nimble.degree")
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                            'Email/registration.html.twig',
                            array('name' => $name)
                        ),
                        'text/html'
                        )
                        
                        ->addPart(
                            $this->renderView(
                                'Email/registration.txt.twig',
                                array('name' => $name)
                            ),
                            'text/plain'
                        );
                        
                        $mailer->send($message);
                        
                        /**
                         * Flush user & school User 
                         */

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();

                        /**
                         * Remove invitation 
                         */
                        $entityManager->remove($inviteCompare);
                        $entityManager->flush();
                        
                        return $this->redirectToRoute('login');
                }
 
            return $this->render(
                'Default/schoolUserSignup.html.twig',
                    array(
                        'userForm' => $userForm->createView(), 

                    )
                );
        }
            return $this->render(
            'Default/schoolUserSignup.html.twig',
                array(
                    'userForm' => $userForm->createView(), 

                )
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
    public function searchQuestion(Request $request, string $searchTerm) {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute("education");
        }
        $dto = new QuestionSearch();
        $dto->setSearch($searchTerm);
        $manager = $this->getDoctrine()->getManager();
        $questions = $this->getDoctrine()->getManager()->getRepository(Question::class)->findByQuestionSearch($dto);
        $serializer = $this->getSerializer();
        $data = $serializer->normalize(
            $questions,
            'json',
            array(
                'groups' => array('public')
            )
        );
        return new JsonResponse(
            $data
        );
    }
    
    public function error(){
    
        return $this->render(
            'Error/error.html.twig',
            array(
           
                
            )
        );
    }
    public function errorInvite(){
        return $this->render(
            'Error/inviteNotFound.html.twig',
            array(
                
            )
        );
    }
    public function getSerializer() : SerializerInterface
    {
        return $this->get('serializer');
    }
    

    public function issue(Request $request){

        /** 
         * Get School & SchoolUser 
         */
        
        $schoolUser = $this->getUser()->getSchoolUser();

        $school = $schoolUser->getSchool();

        $graduateUsers = $school->getGraduateUsers();

        /**
         * Instantiate issuance
         */

        $issuance = new Issuance();


        $issuance->setAuthor($schoolUser);

        $issuance->setSchool($school);

        $issuanceForm = $this->createForm(IssuanceFormType::class, $issuance, ['standalone' => true]);

        $issuanceForm->handleRequest($request);

            if ($issuanceForm->isSubmitted() && $issuanceForm->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($issuance);
                $entityManager->flush();

            };

        $faculties = $this->getDoctrine()->getManager()->getRepository(Faculty::class)->findBySchool($school);
        $issuances = $this->getDoctrine()->getManager()->getRepository(Issuance::class)->findBySchool($school);
        $graduates = $this->getDoctrine()->getManager()->getRepository(GraduateUser::class)->findGradBySchool($school);

        

       
        
        return [
            $issuanceForm,
            $issuances,
            $graduates,
            $faculties,
            $school
        ];
    }

  
    public function SmartfileUploadHandler(Request $request) {

        $user = $this->getUser();

        $schoolUser = $user->getSchoolUser();

        $smartUpload = new SmartUpload();

        $uploadForm = $this->createForm(SmartUploadFormType::class, $smartUpload);

        $smartUpload->setAuthor($schoolUser);     

        $uploadForm->handleRequest($request);

        if ($uploadForm->isSubmitted() && $uploadForm->isValid()) {

            $file = $uploadForm["excelFile"]->getData();

            $filename = $this->generateUniqueFileName() . "." . $file->guessExtension();

            // $path = $file->move(
            //     $this->getParameter('issuances_directory'),
            //     $filename
            // );
    
            $smartUpload->setExcel($filename);

            /**
             * Call reader which instantiate issuance entity
             */

            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($smartUpload);
            $entityManager->flush();
            
            $issuance = $this->smartfFileReader($smartUpload, $request, $user);

            $smartUpload->setIssuance($issuance[0]);
  
            
            /**
             * Persist issuance
             */

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($issuance[0]);
            $entityManager->flush();

                  
                
        }

        // $smartUploads = $this->getDoctrine()->getManager()->getRepository(Upload::class)->findOneByIssuance($issuance);

        return [

            $uploadForm,

           
    
        ]; 

    }

    public function smartfFileReader(SmartUpload $smartUpload, Request $request, User $user){

        $excel = $smartUpload->getExcel();

        $schoolUser = $user->getSchoolUser();

        $school = $schoolUser->getSchool();

        /**
         * Reader path
         */
        $reader = Reader::createFromPath(__DIR__.'/../../public/uploads/issuances/excels/'.$excel, 'r');

        $reader->setHeaderOffset(0);

        $records = $reader->getRecords(['firstname','lastname','email','degreeCode','honor','endingDate']);

        dump($records);

        foreach($records as $offset => $record){

            if($offset == 1){

                $row = explode(';', $record['firstname']);

                $degreeTitle = $this->getDoctrine()->getManager()->getRepository(DegreeTitle::class)->findOneBySchoolAndDegreeCode($school,$row[3]);

                /**
                 * call issue function
                 */
                
                $issuance = $this->smartIssue($request, $degreeTitle[0], $user);

                $issuance[0]->setFaculty($degreeTitle[0]->getFaculty());

                $issuance[0]->setDegreeType($degreeTitle[0]->getDegreeType());

                $issuance[0]->setDegreeField($degreeTitle[0]->getDegreeField());

                $issuance[0]->setClassYearStart($smartUpload->getClassYearStart());

                $issuance[0]->setClassYearEnd($smartUpload->getClassYearEnd());

        
            }

        }

        return [
            $issuance[0],

            
        ];
        
    }

    public function smartIssue(Request $request, DegreeTitle $degreeTitle, User $user){

        
        /** 
         * Get School & SchoolUser 
         */
        
        $schoolUser = $user->getSchoolUser();

        $school = $degreeTitle->getFaculty()->getSchool();

        /**
         * Instantiate issuance
         */

        $issuance = new Issuance();

        $issuance->setAuthor($schoolUser);

        $issuance->setSchool($school);

        $issuance->setDegreeTitle($degreeTitle);

        // $issuanceForm = $this->createForm(SmartIssuanceFormType::class, $issuance, ['standalone' => true]);

        // $issuanceForm->submit($issuance);
   
        return [
            $issuance
            // $issuanceForm
           
        ];
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }


    public function school(School $school){

        $schoolInformation = $this->getDoctrine()->getManager()->getRepository(School::class)->find($school);

        $pod = $this->getDoctrine()->getManager()->getRepository(Degree::class)->findBySchool($school);

        $NbrPod = count($pod); 

        $user = $this->getUser();

        return $this->render(
            'Default/schools.html.twig',
            [
                'school' => $school,
                'schoolOfUser' => $school,
                'user' => $user,
                'nbrGrad' => $NbrPod

            ]
        );

    }
    
}
?>