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
        $user = $this->user();
        $data['user'] = $user;
        $teacherId = $request->query->get('teacherId');
        $teacher = $this->find('Teacher', $teacherId);
        if(!$teacher){
            return $this->redirectToRoute('homepage');
        }
        $timetable = $this->find('Timetable', $teacher->getTimetable()->getId());
        $subjects = $this->findby('Subject', 'user', $user);
        $data['teacher'] = $teacher;
        $data['timetable'] = $timetable;
        return $this->render('classSubject/create.html.twig', $data );


    }

    /**
     * @Route("/classSubject/summary", name="summary_class_subjects")
     */
    public function summaryAction(Request $request)
    {
    	$data = [];
        $user = $this->user();
        $data['user'] = $user;
        $tbl = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tbl);
        $data['timetable'] = $timetable;
        return $this->render('classSubject/summary.html.twig', $data );
    }

    /**
     * @Route("/classSubject/list", name="list_classSubjects")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->user();
        $data['user'] = $user;
        $tbl = $request->query->get('tbl');
        $timetable = $this->em()->getRepository('AppBundle:timetable')
            ->find($tbl);
        $classSubjects = $this->em()->getRepository('AppBundle:ClassSubject')
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
        $classes = $em->getRepository('AppBundle:Classs')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );

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

            $classs = $em->getRepository('AppBundle:Classs')
                ->find($form_data['class']);

            $classSubject->setSubject($subj);
            $classSubject->setCClass($classs);
            $em->persist($classSubject);
            $em->flush();

            $this->addFlash(
                'success',
                'ClassSubject edited successfully!'
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
        $tbl = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tbl);
        $classSubject = $this->find('ClassSubject', $classSubjectId);
        $this->delete($classSubject);
        return $this->redirectToRoute('list_classSubjects', ['tbl' => $tbl]);
    }

        private function em(){
            $em = $this->getDoctrine()->getManager();
            return $em;
        }

        private function find($entity, $id){
            $entity = $this->em()->getRepository("AppBundle:$entity")->find($id);
            return $entity;
        }

        private function findby($entity, $by, $actual){
            $query_string = "findBy$by";
            $entity = $this->em()->getRepository("AppBundle:$entity")->$query_string($actual);
            return $entity;
        }

        private function save($entity){
            $this->em()->persist($entity);
            $this->em()->flush();
            return true;
        }

        private function delete($entity){
            $this->em()->remove($entity);
            $this->em()->flush();
            return true;
        }

        private function user(){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return $user;
        }


}
