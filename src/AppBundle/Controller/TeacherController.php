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
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();
        $tableId = $request->query->get('tbl');
        $TimeTable = $em->getRepository('AppBundle:Timetable')
            ->find($tableId);
        $Teacher = new Teacher();
        $Teacher->setUser($user);

        $teachers = $em->getRepository('AppBundle:Teacher')
            ->findBy(
                array('user' => $user, 'timetable' => $TimeTable),
                array('fName' => 'ASC')
            );

        $data['teachers'] = $teachers;
        $data['timetable'] = $TimeTable;

        $form = $this->createForm(TeacherType::class, $Teacher);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $Teacher->setTimetable($TimeTable);
            try {
                $em->persist($Teacher);
                $em->flush(); 
            }
            catch(\Doctrine\DBAL\DBALException $e) {

                //if the error is not for duplicates, throw the error
                if($e->getErrorCode() == 1062) {
                    $this->addFlash(
                        'success',
                        'Choose another color. That\'s in use!'
                    );
                    return $this->redirectToRoute('list_teachers');
                }

                //otherwise ignore it
            }

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_teacher'
                : 'list_teachers';

            return $this->redirectToRoute($nextAction, ['tbl' => $tableId]);
        } 

        return $this->render('teacher/create.html.twig', ['form' => $form->createView(), 'data' => $data] );

    }

    /**
     * @Route("/teacher/list", name="list_teachers")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $data['user'] = $user;
        $tableId = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($tableId);

        $teachers = $em->getRepository('AppBundle:Teacher')
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
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('AppBundle:Timetable')
            ->find($tableId);

        $teachers = $em->getRepository('AppBundle:Teacher')
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
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $teacher = $em->getRepository('AppBundle:Teacher')
            ->find($teacherId);

        $tableId = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($tableId);

        $teachers = $em->getRepository('AppBundle:Teacher')
            ->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('fName' => 'ASC')
            );
        $data['teachers'] = $teachers;



        $form = $this->createForm(TeacherType::class, $teacher);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            try {
                $em->persist($form_data);
                $em->flush(); 
            }
            catch(\Doctrine\DBAL\DBALException $e) {

                //if the error is not for duplicates, throw the error
                if($e->getErrorCode() == 1062) {
                    $this->addFlash(
                        'success',
                        'Choose another color. That\'s in use!'
                    );
                    return $this->redirectToRoute('list_teachers');
                }

                //otherwise ignore it
            }
            $this->addFlash(
                'success',
                'Teacher edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_teacher'
                : 'list_teachers';

            return $this->redirectToRoute($nextAction, ['tbl' => $tableId]);

        } else {
            $form_data['f_name'] = $teacher->getFName();
            $form_data['l_name'] = $teacher->getLName();
            $form_data['color'] = $teacher->getColor();
            $data['form'] = $form_data;
        }
        $data['teacher'] = $teacher;


        return $this->render('teacher/edit.html.twig', ['form' => $form->createView(), 'data'=>$data,] );

    }

    /**
     * @Route("/teacher/delete/{teacherId}", name="delete_teacher")
     */
    public function deleteAction(Request $request, $teacherId)
    {
    	$data = [];
        $em = $this->getDoctrine()->getManager();
        $tableId = $request->query->get('tbl');

        $teacher = $em->getRepository('AppBundle:Teacher')
        	->find($teacherId);

        $em->remove($teacher);
        $em->flush();

        return $this->redirectToRoute('list_teachers', ['tbl' => $tableId]);

    }

    /**
     * @Route("/teacher/profile/{teacherId}", name="teacher_profile")
     */
    public function teacherAction(Request $request, $teacherId )
    {   
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $teacher = $em->getRepository('AppBundle:Teacher')
            ->find($teacherId);

        $data['teacher'] = $teacher;
        $attendances = $teacher->getAttendances();
        $exams = $teacher->getExams();

        $attendedPoints = 0;
        $presentPoints = 0;
        $absentPoints = 0;
        $totalPoints = 0;

        $daily = [];
        $counter = 0;
        foreach($attendances as $attn){
            $counter += 1;
            if($attn->getMorning() == true && $attn->getAfternoon() == true){
                $attendedPoints += 10;
                $presentPoints = 10;
                $absentPoints = 0;
                $totalPoints += 10;
            }
            if($attn->getMorning() == true && $attn->getAfternoon() == false){
                $attendedPoints += 5;
                $presentPoints = 5;
                $absentPoints = 5;
                $totalPoints += 10;
            }
            if($attn->getMorning() == false && $attn->getAfternoon() == true){
                $attendedPoints += 5;
                $presentPoints = 5;
                $absentPoints = 5;
                $totalPoints += 10;
            }
            if($attn->getMorning() == false && $attn->getAfternoon() == false){
                $attendedPoints += 0;
                $presentPoints = 0;
                $absentPoints = 10;
                $totalPoints += 10;
            }

            $date = $attn->getOnDate()->format('Y-m-d');
            $daily[$counter] = '{ score:"'.$date.'",' . 'present:'.$presentPoints . "," . 'absent:'.$absentPoints . ' }';
            if($counter == 61 ){
                break;
            }
        }

        $examList = [];
        $key = null;
        $limiter = 0;
        foreach($exams as $exam){
            $limiter += 1;

            $key = $exam->getExamCompany()->getId().$exam->getTerm();

            if (isset($examList[$key])) {
                $examList[$key][] = $exam;
            } else {
                $examList[$key] = array($exam);
            }
            if($limiter == 50 ){
                break;
            }
        }


        $data['attendedPoints'] = $attendedPoints;
        $data['presentPoints'] = $presentPoints;
        $data['absentPoints'] = $absentPoints;
        $data['totalPoints'] = $totalPoints;
        $data['daily'] = $daily;
        $data['examList'] = $examList;

        return $this->render('teacher/teacher.html.twig', $data);

    }

    public function isIn($item, $field, $Entity){
        $thisEntity = $em->getRepository('AppBundle:$Entity')
            ->findBy(
                array('field' => $item),
                array('id' => 'ASC')
            );

        if($thisEntity){
            return true;
        } else {
            return false;
        }
    }

}
