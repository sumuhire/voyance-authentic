<?php

namespace App\Controller;

use App\Entity\School;
use App\Entity\Faculty;
use App\Entity\DegreeType;
use App\Entity\DegreeField;
use App\Entity\DegreeTitle;
use App\Entity\StudyTypeTitles;
use App\Form\StudyTypeTitlesFormType;
use App\Form\DegreeFieldFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudiesController extends AbstractController
{
    /**
     * @Route("/studies", name="studies")
     */

    public function studies(Request $request, UserInterface $user, Faculty $faculty) {

        $degreeField=$this->degreeField($request, $user, $faculty);
        // $degreeType=$this->degreeType($request, $user, $faculty);
        // $invite=$this->userInvite($request, $mailer);
        // $faculty=$this->facultyList($request);
        // $reports=$this->reportList($request);
        // $school=$this->school($request);
        // $editSchool=$this->editSchool($request, $user);

        return $this->render(
            'Admin/faculties.html.twig',
            [
                'degreeFields' => $degreeField[0],
                'degreeFieldForm' => $degreeField[1]->createView(),
                'faculty' => $degreeField[2],
                'schoolOfUser' => $degreeField[3],
                // 'degreeTypes' => $degreeType[0],
                // 'degreeTypeForm' => $degreeType[1]->createView(),
                // 'searchForm' => $userList[1]->createView(),
                // 'roleChange' => $userList[2],
                // 'inviteForm'=> $invite->createView(),
                // 'schoolForm'=> $school[0]->createView(),
                // 'schoolUser'=> $school[1],
                // 'ecole'=> $school[2],
                // 'schoolEditForm'=> $editSchool[0]->createView(),
                // 'schoolLogoForm'=> $editSchool[1]->createView(),
                // 'school'=> $editSchool[2],
                // 'faculties'=> $faculty[0],
                // 'facultyForm'=> $faculty[1]->createView(),
                // 'reports' => $reports
                // "warning" => "user or invite already exists"

            ]
        );
    
    }
     


    public function degreeField(Request $request, UserInterface $user, Faculty $faculty)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("education");
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /**
         * Instantiate degreeFieldForm
         */

        $schoolOfUser = $faculty->getSchool();

        /**
         * Instantiate degreeFieldForm
         */

        $degreeField = new DegreeField();

        $degreeFieldForm = $this->createForm(DegreeFieldFormType::class, $degreeField, ['standalone' => true]);

        /**
         * Set schoolUser as author
         */

        $schoolUser = $user->getSchoolUser();

        $degreeField->setAuthor($schoolUser);

        /**
         * Set faculty
         */

        $degreeField->setFaculty($faculty);

        $degreeFieldForm->handleRequest($request);

        if ($degreeFieldForm->isSubmitted() && $degreeFieldForm->isValid()) 
        {       


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($degreeField);
            $entityManager->flush();
        }
        

        /**
         * Find data per faculty
         */

        $degreeFields = $this->getDoctrine()->getManager()->getRepository(DegreeField::class)->findByFaculty($faculty);

        
        return [
            $degreeFields,
            $degreeFieldForm,
            $faculty,
            $schoolOfUser
        
        ];
    }

    public function degreeType(Request $request, DegreeField $degreeField)
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("education");
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $studyTypeTitles = new StudyTypeTitles();
        /**
         * Set schoolUser as author
         */
        $user = $this->getUser();

        $schoolUser = $user->getSchoolUser();

        /**
         * Set faculty & degreeField
         */

        $faculty = $degreeField->getFaculty();

        $school = $faculty->getSchool();


        /**
         * Instantiate degreeTitle
         */

        $degreeTitle = new DegreeTitle();

        $degreeTitle->setSchool($school);
        $degreeTitle->setDegreeField($degreeField);
        $degreeTitle->setFaculty($faculty);

        $studyTypeTitles->addDegreeTitle($degreeTitle);

        $studyTypeTitlesForm = $this->createForm(StudyTypeTitlesFormType::class, $studyTypeTitles, ['standalone' => true]);

        $studyTypeTitlesForm->handleRequest($request);

        if ($studyTypeTitlesForm->isSubmitted() && $studyTypeTitlesForm->isValid()) 
        {       
            $degreeType = $studyTypeTitles->getDegreeType();
            
            $degreeTitle->setDegreeType($degreeType);

            $degreeType->addDegreeField($degreeField);

            /**
             * Link degreeTitles to degreeType & school
             */
            $degreeTitles = $studyTypeTitles->getDegreeTitles();

            foreach($degreeTitles as $degree){
                
                $school->addDegreeTitle($degree);
                $degreeType->addDegreeTitle($degree);
                $degreeField->addDegreeTitle($degree);
                $faculty->addDegreeTitle($degree);


            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($studyTypeTitles);
            $entityManager->flush();
            
        }
       
        /**
         * Get degreetypes
         */

        $degreeTypes = $this->getDoctrine()->getManager()->getRepository(DegreeType::class)->findAll();

        return $this->render(
            'Admin/facultySettings.html.twig',
                [
                    'degreeTypes' => $degreeTypes,
                    'degreeTypeForm' => $studyTypeTitlesForm->createView(),
                    'faculty' => $faculty,
                    'degreeField' => $degreeField,
                    'schoolUser' => $schoolUser,
                    'schoolOfUser' => $school
                ]
            );
    }

}
