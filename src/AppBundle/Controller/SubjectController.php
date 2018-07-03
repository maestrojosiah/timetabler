<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ChildSubject;
use AppBundle\Entity\Subject;
use AppBundle\Form\SubjectType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\VarDumper\VarDumper;

class SubjectController extends Controller
{
    /**
     * @Route("/subject/create", name="add_subject")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $user = $this->user();
        $data['user'] = $user;
        $tableId = $request->query->get('tbl');
        $TimeTable = $this->find('Timetable', $tableId);
        $subjects = $this->em()->getRepository('AppBundle:Subject')
            ->findBy(
                array('user'=>$user, 'timetable'=>$TimeTable),
                array('id' => 'ASC')
            );
        $data['timetable'] = $TimeTable;
        $data['subjects'] = $subjects;
	      return $this->render('subject/create.html.twig', ['data' => $data] );

    }

    /**
     * @Route("/subject/list", name="list_subjects")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->user();
        $data['user'] = $user;
        $tbl = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tbl);
        $subjects = $this->em()->getRepository('AppBundle:Subject')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('id' => 'ASC')
            );
        $data['subjects'] = $subjects;
        $data['timetable'] = $timetable;
        return $this->render('subject/list.html.twig', $data );
    }

    /**
     * @Route("/subject/edit/{subjectId}", name="edit_subject")
     */
    public function editAction(Request $request, $subjectId)
    {
        $data = [];
        $subject = $this->em()->getRepository('AppBundle:Subject')
            ->find($subjectId);
        $tableId = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tableId);
        $data['subject'] = $subject;
        $data['timetable'] = $timetable;
        return $this->render('subject/edit.html.twig', ['data' => $data] );

    }

    /**
     * @Route("/subject/delete/{subjectId}", name="delete_subject")
     */
    public function deleteAction(Request $request, $subjectId)
    {
        $data = [];

        $tableId = $request->query->get('tbl');

        $subject = $this->em()->getRepository('AppBundle:Subject')
            ->find($subjectId);

            // exit(VarDumper::dump($childrenString));

        $this->em()->remove($subject);
        $this->em()->flush();

        return $this->redirectToRoute('list_subjects', ['tbl' => $tableId]);

    }

    /**
     * @Route("/timetable/subject/choose", name="choose_table_for_subject")
     */
    public function chooseAction(Request $request)
    {

        return $this->render('subject/choose.html.twig');

    }

    /**
     * @Route("/timetable/subject/list", name="choose_list_for_subject")
     */
    public function chooseListAction(Request $request)
    {

        return $this->render('subject/choose_list.html.twig');

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

    private function findandlimit($entity, $by, $actual, $limit, $order){
        $entity = $this->em()->getRepository("AppBundle:$entity")
            ->findBy(
                array($by => $actual),
                array('id' => $order),
                $limit
            );
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
