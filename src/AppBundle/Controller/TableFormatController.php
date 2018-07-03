<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TableFormat;
use AppBundle\Form\TableFormatType;

//issue: deletion
class TableFormatController extends Controller
{
    /**
     * @Route("/tableFormat/create", name="add_table_format")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $user = $this->user();
        $data['user'] = $user;

        $tbl = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tbl);
        $data['timetable'] = $timetable;
        $tableFormats = $this->em()->getRepository('AppBundle:TableFormat')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('id' => 'ASC')
            );
        $data['tableFormats'] = $tableFormats;
        return $this->render('tableFormat/create.html.twig', $data );
    }

    /**
     * @Route("/timetable/format/choose", name="choose_table_for_format")
     */
    public function chooseAction(Request $request)
    {
        return $this->render('tableFormat/choose.html.twig');
    }

    /**
     * @Route("/tableFormat/list", name="list_tableFormats")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->user();
        $data['user'] = $user;
        $tbl = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tbl);
        $tableFormats = $this->em()->getRepository('AppBundle:TableFormat')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('id' => 'ASC')
            );
        $data['tableFormats'] = $tableFormats;
        $data['timetable'] = $timetable;
        return $this->render('tableFormat/list.html.twig', $data );
    }

    /**
     * @Route("/tableFormat/edit/{tableFormatId}", name="edit_tableFormat")
     */
    public function editAction(Request $request, $tableFormatId)
    {
        $data = [];
        $tableFormat = $this->find('TableFormat', $tableFormatId);
        $tbl = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tbl);
        $data['timetable'] = $timetable;
        $data['tableformat'] = $tableFormat;
        return $this->render('tableFormat/edit.html.twig', $data );
    }

    /**
     * @Route("/tableFormat/delete/{tableFormatId}", name="delete_tableFormat")
     */
    public function deleteAction(Request $request, $tableFormatId)
    {
        $data = [];
        $tbl = $request->query->get('tbl');
        $tableFormat = $this->find('TableFormat', $tableFormatId);
        $this->delete($tableFormat);
        return $this->redirectToRoute('list_tableFormats', ['tbl' => $tbl]);

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
