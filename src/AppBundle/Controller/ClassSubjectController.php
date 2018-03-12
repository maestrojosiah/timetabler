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
        if(!$teacher){
            return $this->redirectToRoute('homepage');
        }
        $timetable = $em->getRepository('AppBundle:timetable')
            ->find($teacher->getTimetable()->getId());

        $classsubjects = $em->getRepository('AppBundle:ClassSubject')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );

        $subjects = $em->getRepository('AppBundle:Subject')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );

        $teachers = $em->getRepository('AppBundle:Teacher')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );


        $data['teacher'] = $teacher;
        $data['timetable'] = $timetable;
        $data['subjects'] = $subjects;
        $data['teachers'] = $teachers;

        $classes = $em->getRepository('AppBundle:Classs')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );
        $data['classes'] = $classes;

        $data['classsubjects'] = $classsubjects;

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

            $classs = $em->getRepository('AppBundle:Classs')
                ->find($form_data['class']);

            $tr = $em->getRepository('AppBundle:Teacher')
                ->find($teacherId);
            $classSubject->setSubject($subj);
            $classSubject->setCClass($classs);
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
        $tbl = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:timetable')
            ->find($tbl);

        $classSubjects = $em->getRepository('AppBundle:ClassSubject')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('id' => 'ASC')
            );

        $data['classSubjects'] = $classSubjects;
        $data['timetable'] = $timetable;

        return $this->render('classSubject/list.html.twig', $data );

    }

    /**
     * @Route("/classSubject/edit/{classSubjectId}", name="edit_classSubject")
     */
    public function editAction(Request $request, $classSubjectId)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;
        $data['form'] = [];

        $em = $this->getDoctrine()->getManager();

        $classSubject = $em->getRepository('AppBundle:ClassSubject')
            ->find($classSubjectId);

        $teacher = $classSubject->getTeacher();
        $timetable = $em->getRepository('AppBundle:timetable')
            ->find($teacher->getTimetable()->getId());

        $data['timetable'] = $timetable;
        $recordedRange = $timetable->getClassRange();
        $separate = explode('-', $recordedRange);

        $classes = range($separate[0], $separate[1]);

        $data['teacher'] = $teacher;
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

            $classSubject->setSubject($subj);
            $classSubject->setCClass($form_data['class']);
            $em->persist($classSubject);
            $em->flush();

            $this->addFlash(
                'success',
                'ClassSubject created successfully!'
            );

            return $this->redirectToRoute('list_classSubjects', ['teacherId' => $teacher->getId(), 'tbl' => $timetable->getId()]);
        } else {
            $data['subj'] = $classSubject->getSubject()->getSTitle();
            $data['cls'] = $classSubject->getCClass();
            $data['form'] = $form;
        }

    
        return $this->render('classSubject/edit.html.twig', $data );

    }


    /**
     * @Route("/classSubject/delete/{classSubjectId}", name="delete_classSubject")
     */
    public function deleteAction(Request $request, $classSubjectId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $tbl = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:timetable')
            ->find($tbl);

        $classSubject = $em->getRepository('AppBundle:ClassSubject')
            ->find($classSubjectId);

        $em->remove($classSubject);
        $em->flush();

        return $this->redirectToRoute('list_classSubjects', ['tbl' => $tbl]);

    }


}