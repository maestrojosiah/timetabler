<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\Attendance;
use AppBundle\Form\TeacherType;


class TeacherController extends Controller
{
    /**
     * @Route("/teacher/create", name="add_teacher")
     */
    public function createAction(Request $request)
    {
        $data = [];
        $user = $this->user();
        $data['user'] = $user;
        $tableId = $request->query->get('tbl');
        $TimeTable = $this->find('Timetable', $tableId);
        $teachers = $this->em()->getRepository('AppBundle:Teacher')
            ->findBy(
                array('user' => $user, 'timetable' => $TimeTable),
                array('fName' => 'ASC')
            );
        $data['teachers'] = $teachers;
        $data['timetable'] = $TimeTable;
        return $this->render('teacher/create.html.twig', ['data' => $data] );
    }

    /**
     * @Route("/teacher/list", name="list_teachers")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->user();
        $data['user'] = $user;
        $tableId = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tableId);
        $teachers = $this->em()->getRepository('AppBundle:Teacher')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('fName' => 'ASC')
            );
        $data['teachers'] = $teachers;
        $data['timetable'] = $timetable;

        return $this->render('teacher/list.html.twig', $data );

    }

    /**
     * @Route("/timetable/teacher/choose", name="choose_table_for_teacher")
     */
    public function chooseAction(Request $request)
    {

        return $this->render('teacher/choose.html.twig');

    }

    /**
     * @Route("/timetable/teacher/choose/list", name="choose_table_for_teacher_list")
     */
    public function chooseListAction(Request $request)
    {

        return $this->render('teacher/teacherlist.html.twig');

    }

    /**
     * @Route("/teacher/show/{tableId}", name="list_teachers_for_timetable")
     */
    public function tablesListAction(Request $request, $classId)
    {
        $data = [];
        $user = $this->user();
        $data['user'] = $user;
        $table = $this->find('Timetable', $tableId);
        $teachers = $this->em()->getRepository('AppBundle:Teacher')
            ->findBy(
                array('user' => $user, 'table_id' => $table),
                array('id' => 'ASC')
            );
        $data['teachers'] = $teachers;
        return $this->render('teacher/list.html.twig', $data );
    }

    /**
     * @Route("/teacher/edit/{teacherId}", name="edit_teacher")
     */
    public function editAction(Request $request, $teacherId)
    {
        $data = [];
        $user = $this->user();
        $teacher = $this->find('Teacher', $teacherId);
        $tableId = $request->query->get('tbl');
        $timetable = $this->find('Timetable', $tableId);
        $teachers = $this->em()->getRepository('AppBundle:Teacher')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('fName' => 'ASC')
            );
        $data['teachers'] = $teachers;
        $data['teacher'] = $teacher;
        $data['timetable'] = $timetable;
        return $this->render('teacher/edit.html.twig', ['data'=>$data] );
    }

    /**
     * @Route("/teacher/delete/{teacherId}", name="delete_teacher")
     */
    public function deleteAction(Request $request, $teacherId)
    {
    	  $data = [];
        $tableId = $request->query->get('tbl');
        $teacher = $this->find('Teacher', $teacherId);
        $this->delete($teacher);
        return $this->redirectToRoute('list_teachers', ['tbl' => $tableId]);
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
