<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ClassSubject;
use AppBundle\Form\ClassSubjectType;


class ClassSubjectController extends Controller
{
    /**
     * @Route("/classSubject/create", name="assign_subjects")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;
        $data['form'] = [];

        $em = $this->getDoctrine()->getManager();

        $classSubject = new ClassSubject();
        $classSubject->setUser($user);

        $teacherId = $request->query->get('teacherId');
        $teacher = $em->getRepository('AppBundle:Teacher')
            ->find($teacherId);

        $timetable = $em->getRepository('AppBundle:timetable')
            ->find($teacher->getTimetable()->getId());

        $data['teacher'] = $teacher;
        $data['timetable'] = $timetable;
        $recordedRange = $timetable->getClassRange();
        $separate = explode('-', $recordedRange);

        $classes = range($separate[0], $separate[1]);
        $data['classes'] = $classes;

        $form = $this->createFormBuilder()
            ->add('subject')
            ->add('class')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;
            $subj = $em->getRepository('AppBundle:Subject')
                ->find($form_data['subject']);

            $tr = $em->getRepository('AppBundle:Teacher')
                ->find($teacherId);
            $classSubject->setSubject($subj);
            $classSubject->setCClass($form_data['class']);
            $classSubject->setTimetable($tr->getTimetable());
            $classSubject->setTeacher($tr);
            $em->persist($classSubject);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'ClassSubject created successfully!'
        	);

            return $this->redirectToRoute('assign_subjects', ['teacherId' => $teacherId]);
		} else {
            $data['form'] = $form;
        }

	
        // replace this example code with whatever you need
        return $this->render('classSubject/create.html.twig', $data );


    }

    /**
     * @Route("/classSubject/list", name="list_classSubjects")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $classSubjects = $em->getRepository('AppBundle:ClassSubject')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );

        $data['classSubjects'] = $classSubjects;

        return $this->render('classSubject/list.html.twig', $data );

    }

    /**
     * @Route("/classSubject/edit/{classSubjectId}", name="edit_classSubject")
     */
    public function editAction(Request $request, $classSubjectId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $classSubject = $em->getRepository('AppBundle:ClassSubject')
            ->find($classSubjectId);


        $form = $this->createForm(ClassSubjectType::class, $classSubject);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            $em->persist($form_data);
            $em->flush();

            $this->addFlash(
                'success',
                'ClassSubject edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_classSubject'
                : 'list_classSubjects';

            return $this->redirectToRoute($nextAction);

        } else {
            $form_data['class_number'] = $classSubject->getClassNumber();
            $form_data['class_wing'] = $classSubject->getClassWing();
            $form_data['class_teacher'] = $classSubject->getClassTeacher();
            $data['form'] = $form_data;
        }
        $data['classSubject'] = $classSubject;


        return $this->render('class/edit.html.twig', ['form' => $form->createView(), $data,] );

    }


    /**
     * @Route("/classSubject/delete/{classSubjectId}", name="delete_classSubject")
     */
    public function deleteAction(Request $request, $classSubjectId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $classSubject = $em->getRepository('AppBundle:ClassSubject')
            ->find($classSubjectId);

        $em->remove($classSubject);
        $em->flush();

        return $this->redirectToRoute('list_classSubjects');

    }


}