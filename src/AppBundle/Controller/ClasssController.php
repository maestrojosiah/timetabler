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
        $user = $this->user();
        $tableId = $request->query->get('tbl');
        $TimeTable = $this->find('Timetable', $tableId);
        $classes = $this->em()->getRepository('AppBundle:Classs')
            ->findBy(
                array('user'=>$user, 'timetable'=>$TimeTable),
                array('id' => 'ASC')
            );
        $data['timetable'] = $TimeTable;
        $data['classes'] = $classes;
        return $this->render('classs/create.html.twig',['data' => $data] );
    }

    /**
     * @Route("/classs/list", name="list_classes")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->user();
        $tbl = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tbl);
        $classes = $this->em()->getRepository('AppBundle:Classs')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('id' => 'ASC')
            );
        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['user'] = $user;
        return $this->render('classs/list.html.twig', $data );
    }

    /**
     * @Route("/classs/edit/{classsId}", name="edit_classs")
     */
    public function editAction(Request $request, $classsId)
    {
        $data = [];
        $classs = $this->em()->getRepository('AppBundle:Classs')
            ->find($classsId);
        $tableId = $request->query->get('tbl');
        $data['classs'] = $classs;
        $data['timetable'] = $this->find('Timetable', $tableId);
        return $this->render('classs/edit.html.twig', ['data' => $data] );

    }

    /**
     * @Route("/classs/delete/{classsId}", name="delete_classs")
     */
    public function deleteAction(Request $request, $classsId)
    {
        $data = [];
        $tableId = $request->query->get('tbl');
        $classs = $this->find('Classs', $classsId);
        $this->delete($classs);
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
