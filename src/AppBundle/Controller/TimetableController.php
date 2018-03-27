<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Timetable;
use AppBundle\Entity\Attendance;
use AppBundle\Form\TimetableType;
use \DateInterval;

class TimetableController extends Controller
{
    /**
     * @Route("/timetable/create", name="add_timetable")
     */
    public function createAction(Request $request)
    {

        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $Timetable = new Timetable();
        $Timetable->setUser($user);

        $form = $this->createForm(TimetableType::class, $Timetable);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($Timetable);
            $em->flush();
            return $this->redirectToRoute('add_classs', ['tbl' => $Timetable->getId()]);
        } 

        return $this->render('timetable/create.html.twig',['form' => $form->createView(), 'data' => $data] );

    }

    /**
     * @Route("/timetable/list", name="list_timetables")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $timetables = $em->getRepository('AppBundle:Timetable')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );

        $data['timetables'] = $timetables;

        return $this->render('timetable/list.html.twig', $data );

    }

    /**
     * @Route("/timetable/show/{classId}", name= "list_timetables_from_class")
     */
    public function classListAction(Request $request, $classId)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();
        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $timetables = $em->getRepository('AppBundle:Timetable')
            ->findBy(
                array('user' => $user, 'classs' => $class),
                array('fName' => 'ASC')
            );

        $data['timetables'] = $timetables;

        return $this->render('timetable/list.html.twig', $data );

    }

    /**
     * @Route("/timetable/edit/{timetableId}", name="edit_timetable")
     */
    public function editAction(Request $request, $timetableId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($timetableId);


        $form = $this->createForm(TimetableType::class, $timetable);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            $em->persist($form_data);
            $em->flush();

            $this->addFlash(
                'success',
                'Timetable edited successfully!'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'add_timetable'
                : 'list_timetables';

            return $this->redirectToRoute($nextAction);

        } else {
            $form_data['title'] = $timetable->getTitle();
            $form_data['time'] = $timetable->getTime();
            $data['form'] = $form_data;
        }

        $data['timetable'] = $timetable;


        return $this->render('timetable/edit.html.twig', ['form' => $form->createView(), $data,] );

    }

    /**
     * @Route("/timetable/delete/{timetableId}", name="delete_timetable")
     */
    public function deleteAction(Request $request, $timetableId)
    {
    	$data = [];
        $em = $this->getDoctrine()->getManager();

        $timetable = $em->getRepository('AppBundle:Timetable')
        	->find($timetableId);

        $em->remove($timetable);
        $em->flush();

        return $this->redirectToRoute('list_timetables');

    }

    /**
     * @Route("/timetable/full", name="view_timetable")
     */
    public function viewAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $tableId = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($tableId);

        $classes = $em->getRepository('AppBundle:Classs')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );

        $tableformats = $em->getRepository('AppBundle:TableFormat')
            ->findByTimetable($timetable);

        if(!$tableformats){
            $this->addFlash(
                'error',
                'Please setup the timetable order of events!'
            );            
            return $this->redirectToRoute('add_table_format', ['tbl' => $timetable->getId()]);            
        }
        $lessons = $em->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $lesson_series = [];
        $start_time = $timetable->getTime()->format('H:i');
        $current_time = $timetable->getTime()->format('H:i');
        foreach ($tableformats as $key => $value) {
            $first_part = $current_time;
            $current_time = $this->add_minutes($value->getDuration(), $current_time);
            $lesson_series[] = $first_part .'-'.$current_time.'|'.$value->getId();

        }
        $teachers = $em->getRepository('AppBundle:Teacher')
            ->findByTimetable($timetable);

        $teachers_list = [];
        foreach ($teachers as $key => $teacher) {
            $teachers_list[$teacher->getId()] = $key+1;
        }

        $items = [];
        foreach($lessons as $lesson){
            $subjectEntity = $lesson->getSubject();
            $teacherEntity = $lesson->getTeacher();
            $items[$lesson->getTableFormatColumn().".".$lesson->getClass().".".$lesson->getDay()] = $subjectEntity->getSTitle()."|".$teacherEntity->getColor()."|".$teachers_list[$teacherEntity->getId()];
        }

        $timetablers = $em->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $data['timetablers'] = $timetablers;
        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;

        return $this->render('timetable/tableformat.html.twig', $data);

    }

    /**
     * @Route("/timetable/pdf", name="pdf_timetable")
     */
    public function pdfAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $tableId = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($tableId);

        $classes = $em->getRepository('AppBundle:Classs')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );

        $tableformats = $em->getRepository('AppBundle:TableFormat')
            ->findByTimetable($timetable);

        $lessons = $em->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $lesson_series = [];
        $start_time = $timetable->getTime()->format('H:i');
        $current_time = $timetable->getTime()->format('H:i');
        foreach ($tableformats as $key => $value) {
            $first_part = $current_time;
            $current_time = $this->add_minutes($value->getDuration(), $current_time);
            $lesson_series[] = $first_part .'-'.$current_time.'|'.$value->getId();

        }

        $teachers = $em->getRepository('AppBundle:Teacher')
            ->findByTimetable($timetable);

        $teachers_list = [];
        foreach ($teachers as $key => $teacher) {
            $teachers_list[$teacher->getId()] = $key+1;
        }


        $items = [];
        foreach($lessons as $lesson){
            $subjectEntity = $lesson->getSubject();
            $teacherEntity = $lesson->getTeacher();
            $items[$lesson->getTableFormatColumn().".".$lesson->getClass().".".$lesson->getDay()] = $subjectEntity->getSTitle()."|".$teacherEntity->getColor()."|".$teachers_list[$teacherEntity->getId()];
        }

        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;

        // return $this->render('timetable/tableformat.html.twig', $data);
        $appPath = $this->container->getParameter('kernel.root_dir');

        $html = $this->renderView('pdf/timetable.html.twig', $data);

        $filename = sprintf("timetable-%s.pdf", date('Ymd~his'));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array('orientation'=>'Landscape')),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }

    /**
     * @Route("/timetable/image", name="image_timetable")
     */
    public function imageAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $tableId = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($tableId);

        $classes = $em->getRepository('AppBundle:Classs')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );


        $tableformats = $em->getRepository('AppBundle:TableFormat')
            ->findByTimetable($timetable);

        $lessons = $em->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $lesson_series = [];
        $start_time = $timetable->getTime()->format('H:i');
        $current_time = $timetable->getTime()->format('H:i');
        foreach ($tableformats as $key => $value) {
            $first_part = $current_time;
            $current_time = $this->add_minutes($value->getDuration(), $current_time);
            $lesson_series[] = $first_part .'-'.$current_time.'|'.$value->getId();

        }

        $teachers = $em->getRepository('AppBundle:Teacher')
            ->findByTimetable($timetable);

        $teachers_list = [];
        foreach ($teachers as $key => $teacher) {
            $teachers_list[$teacher->getId()] = $key+1;
        }

        $items = [];
        foreach($lessons as $lesson){
            $subjectEntity = $em->getRepository('AppBundle:Subject')
                ->find($lesson->getSubject());
            $teacherEntity = $em->getRepository('AppBundle:Teacher')
                ->find($lesson->getTeacher());
            $items[$lesson->getTableFormatColumn().".".$lesson->getClass().".".$lesson->getDay()] = $subjectEntity->getSTitle()."|".$teacherEntity->getColor()."|".$teachers_list[$teacherEntity->getId()];
        }

        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;

        // return $this->render('timetable/tableformat.html.twig', $data);
        $appPath = $this->container->getParameter('kernel.root_dir');

        $html = $this->renderView('pdf/timetable_img.html.twig', $data);

        $filename = sprintf("timetable-%s.jpg", date('Ymd~his'));

        return new Response(
            $this->get('knp_snappy.image')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'image/jpg',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }

    /**
     * @Route("/choose/timetable/view", name="choose_timetable_to_view")
     */
    public function chooseAction(Request $request)
    {

        return $this->render('timetable/choose.html.twig');

    }

    /**
     * @Route("/timetable/profile/{timetableId}", name="timetable_profile")
     */
    public function timetableAction(Request $request, $timetableId )
    {   
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($timetableId);

        $data['timetable'] = $timetable;
        $attendances = $timetable->getAttendances();
        $exams = $timetable->getExams();

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

        return $this->render('timetable/timetable.html.twig', $data);

    }

    private function add_minutes($minutes_to_add, $given_time){

        $time = new \DateTime($given_time);
        $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

        $new_time = $time->format('H:i');

        return $new_time;
    }

}
