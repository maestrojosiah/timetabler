<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Classs;
use AppBundle\Form\ClasssType;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClasssController extends Controller
{
    /**
     * @Route("/classs/create", name="add_classs")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;
        $em = $this->getDoctrine()->getManager();

        $tableId = $request->query->get('tbl');
        $TimeTable = $em->getRepository('AppBundle:Timetable')
            ->find($tableId);

        $classes = $em->getRepository('AppBundle:Classs')
            ->findBy(
                array('user'=>$user, 'timetable'=>$TimeTable),
                array('id' => 'ASC')
            );


        $classs = new Classs();
        $classs->setUser($user);
        $classs->setTimetable($TimeTable);
        $data['timetable'] = $TimeTable;
        $data['classes'] = $classes;

        $form = $this->createForm(ClasssType::class, $classs);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $safe_class = trim(str_replace(" ", "", $form_data->getCTitle()));
            $classs->setCTitle($safe_class);
            $em->persist($classs);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'Classs created successfully!'
        	);

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_classs'
                : 'list_classes';

            return $this->redirectToRoute($nextAction, ['tbl' => $tableId]);

		} 

	
        // replace this example code with whatever you need
        return $this->render('classs/create.html.twig',['form' => $form->createView(), 'data' => $data] );

    }

    /**
     * @Route("/classs/list", name="list_classes")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();
        $tbl = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:timetable')
            ->find($tbl);

        $classes = $em->getRepository('AppBundle:Classs')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('id' => 'ASC')
            );

        $data['classes'] = $classes;
        $data['timetable'] = $timetable;

        return $this->render('classs/list.html.twig', $data );

    }

    /**
     * @Route("/classs/edit/{classsId}", name="edit_classs")
     */
    public function editAction(Request $request, $classsId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $classs = $em->getRepository('AppBundle:Classs')
            ->find($classsId);

        $tableId = $request->query->get('tbl');


        $form = $this->createForm(ClasssType::class, $classs);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;
            $safe_class = trim(str_replace(" ", "", $form_data->getCTitle()));
            $form_data->setCTitle($safe_class);

            $em->persist($form_data);
            $em->flush();

            $this->addFlash(
                'success',
                'Classs edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_classs'
                : 'list_classes';

            return $this->redirectToRoute($nextAction, ['tbl' => $tableId]);

        } else {
            $form_data['s_title'] = $classs->getCTitle();
            $data['form'] = $form_data;
        }

        $data['classs'] = $classs;


        return $this->render('classs/edit.html.twig', ['form' => $form->createView(), 'data' => $data,] );

    }

    /**
     * @Route("/classs/delete/{classsId}", name="delete_classs")
     */
    public function deleteAction(Request $request, $classsId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $tableId = $request->query->get('tbl');

        $classs = $em->getRepository('AppBundle:Classs')
            ->find($classsId);

            // exit(VarDumper::dump($childrenString));

        $em->remove($classs);
        $em->flush();

        return $this->redirectToRoute('list_classes', ['tbl' => $tableId]);

    }
    
    /**
     * @Route("/timetable/classs/choose", name="choose_table_for_classs")
     */
    public function chooseAction(Request $request)
    {

        return $this->render('classs/choose.html.twig');

    }
    
    /**
     * @Route("/timetable/classs/list", name="choose_list_for_classs")
     */
    public function chooseListAction(Request $request)
    {

        return $this->render('classs/choose_list.html.twig');

    }

    /**
     * @Route("/classs/profile/{classsId}/{classId}", name="classs_profile")
     */
    public function classsProfileAction(Request $request, $classsId, $classId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $classs = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $classs = $em->getRepository('AppBundle:Classs')
            ->find($classsId);
        
        $exams = $em->getRepository('AppBundle:Exam')
            ->findBy(
                array('classs' => $classs, 'classs' => $classs),
                array('term' => 'ASC')
            );

        $using = 'classs';
        if(!$classs){
            $classs = $em->getRepository('AppBundle:ChildClasss')
                ->find($classsId);

            $exams = $em->getRepository('AppBundle:Exam')
                ->findBy(
                    array('classs' => $classs, 'childClasss' => $classs),
                    array('term' => 'ASC')
                );
            $using = 'childClasss';
        }


        $data['classs'] = $classs;

        $examList = [];
        $examCompanies = [];
        $index = 0;
        foreach($exams as $exam){
            $examList[] = $exam;
            $examCompanies[$exam->getTerm()."_".$index] = $exam->getExamCompany()->getId();
            $index++;
        }

        $noOfStudents = count($classs->getStudents());

        $summations = [];
        foreach($examCompanies as $term => $singleCompany){
            $thisTerm = explode("_", $term)[0];
            $thisCompany = $em->getRepository('AppBundle:ExamCompany')
                ->find($singleCompany);

            if($using == 'classs'){
              $sumMarksForThisCompany = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForClasss($classs, $singleCompany, $thisTerm, $classs);
            } else {
              $sumMarksForThisCompany = $em->getRepository('AppBundle:Exam')
                ->getTotalMarksForChildClasss($classs, $singleCompany, $thisTerm, $classs);
            }
            $meanScore = $sumMarksForThisCompany / $noOfStudents;
            $summations[$thisCompany->getCompanyName()." Term $thisTerm"] = round($meanScore, 2);
        }

        $data['exams'] = $summations;
        $data['examList'] = $examList;
        $data['test'] = $examCompanies;

        return $this->render('classs/classs.html.twig', $data );
        
    }

    /**
     * @Route("/student/ajax/record", name="ajax_save_classs")
     */
    public function recordAction(Request $request)
    {

        if($request->request->get('classcTitle')){
            $data = [];
            $classcTitle = $request->request->get('classcTitle');
            $role = $request->request->get('role');
            $parentName = $request->request->get('parentName');
            $children = $request->request->get('children');
            $outOf1 = $request->request->get('outOf1');
            $outOf2 = $request->request->get('outOf2');

            $user = $this->get('security.token_storage')->getToken()->getUser();
          
            $em = $this->getDoctrine()->getManager();


            if($role == "parent"){ // or null

                $classsPar = new Classs();
                $classsPar->setClasscTitle($parentName);
                $classsPar->setOutOf('children');
                $classsPar->setUser($user);
                $em->persist($classsPar);
                $em->flush();

                $childrens = explode("_", $children);
                $pointer = 0;
                foreach($childrens as $child){
                    $pointer += 1;
                    $classs = new ChildClasss();
                    $classs->setClasscTitle($child);
                    $classs->setParent($classsPar);
                    if($pointer == 1){
                        $classs->setOutOf($outOf1);
                    } else if ($pointer == 2 ){
                        $classs->setOutOf($outOf2);
                    }
                    
                    $classs->setUser($user);

                    $em->persist($classs);
                    $em->flush();
                }




            } else {
                $classs = new Classs();
                $classs->setClasscTitle($classcTitle);
                $classs->setOutOf(100);
                $classs->setUser($user);

                $em->persist($classs);
                $em->flush();
            }



            $data['classcTitle'] = $classcTitle;
            $data['role'] = $role;
            $data['children'] = $children;
            $data['outOf1'] = $outOf1;
            $data['outOf2'] = $outOf2;
            $data['parentName'] = $parentName;
        }

        $this->addFlash(
            'success',
            'Classs added successfully!'
        );

            return new JsonResponse($data);

    }

    /**
     * @Route("/student/ajax/edit", name="ajax_edit_classs")
     */
    public function ajaxEditAction(Request $request)
    {

        if($request->request->get('classcTitle')){
            $data = [];
            $id = $request->request->get('id');
            $classcTitle = $request->request->get('classcTitle');
            $role = $request->request->get('role');
            $parentName = $request->request->get('parentName');
            $children = $request->request->get('children');
            $outOf1 = $request->request->get('outOf1');
            $outOf2 = $request->request->get('outOf2');

            $user = $this->get('security.token_storage')->getToken()->getUser();
          
            $em = $this->getDoctrine()->getManager();

            if($role == "parent"){ // or null

                $classsPar = $em->getRepository('AppBundle:Classs')
                    ->find($id);
                $classsPar->setClasscTitle($parentName);
                $classsPar->setOutOf('children');
                $classsPar->setUser($user);
                $em->persist($classsPar);
                $em->flush();

                $childrens = $classsPar->getChildClassss();
                $pointer = 0;
                foreach($childrens as $classs){
                    $pointer += 1;
                    $classs->setParent($classsPar);
                    if($pointer == 1){
                        $classs->setOutOf($outOf1);
                    } else if ($pointer == 2 ){
                        $classs->setOutOf($outOf2);
                    }
                    
                    $classs->setUser($user);

                    $em->persist($classs);
                    $em->flush();
                }




            } else {
                $classs = new Classs();
                $classs->setClasscTitle($classcTitle);
                $classs->setOutOf(100);
                $classs->setUser($user);

                $em->persist($classs);
                $em->flush();
            }


            $data['classcTitle'] = $classcTitle;
            $data['role'] = $role;
            $data['children'] = $children;
            $data['outOf1'] = $outOf1;
            $data['outOf2'] = $outOf2;
            $data['parentName'] = $parentName;
        }

        $this->addFlash(
            'success',
            'Classs added successfully!'
        );

            return new JsonResponse($data);

    }



}