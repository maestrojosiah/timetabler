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
        $user = $this->find_user();
        $data['user'] = $user;
        $Timetable = new Timetable();
        $Timetable->setUser($user);

        $form = $this->createForm(TimetableType::class, $Timetable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($Timetable);
            $this->em()->flush();
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
        $user = $this->find_user();
        $data['user'] = $user;
        $timetables = $this->get_timetables($user);
        $data['timetables'] = $timetables;
        return $this->render('timetable/list.html.twig', $data );
    }

    /**
     * @Route("/timetable/show/{classId}", name= "list_timetables_from_class")
     */
    public function classListAction(Request $request, $classId)
    {
        $data = [];
        $user = $this->find_user();
        $data['user'] = $user;

        $class = $this->find_class($classId);

        $timetables = $this->get_timetables_class($user, $class);

        $data['timetables'] = $timetables;

        return $this->render('timetable/list.html.twig', $data );

    }

    /**
     * @Route("/timetable/edit/{timetableId}", name="edit_timetable")
     */
    public function editAction(Request $request, $timetableId)
    {
        $data = [];

        $timetable = $this->find_timetable($timetableId);

        $form = $this->createForm(TimetableType::class, $timetable);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            $data['form'] = $form_data;

            $this->em()->persist($form_data);
            $this->em()->flush();

            $this->addFlash('success', 'Timetable edited successfully!');

            return $this->redirectToRoute('list_timetables');

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


        $timetable = $this->find_timetable($timetableId);

        $this->em()->remove($timetable);
        $this->em()->flush();

        return $this->redirectToRoute('list_timetables');

    }

    /**
     * @Route("/timetable/full", name="view_timetable")
     */
    public function viewAction(Request $request)
    {
        ini_set('memory_limit', '-1');
        $data = [];
        $data['show_sth'] = "show_auto_button";

        $user = $this->find_user();
        $tableId = $request->query->get('tbl');
        $timetable = $this->find_timetable($tableId);
        $classes = $this->find_classes($user, $timetable);
        $tableformats = $this->find_table_formats($timetable);
        $lesson_tableformats = $this->em()->getRepository('AppBundle:TableFormat')
          ->countPossibleLessons($user, $timetable);
        $lessons = $this->find_lessons($timetable);
        $teachers = $this->find_teachers($timetable);

        $existing_download = $this->em()->getRepository('AppBundle:Download')
          ->findOneBy(
              array('timetable' => $timetable),
              array('id' => 'DESC')
          );

        if(!$tableformats){
            $this->addFlash('error', 'Please setup the timetable order of events!');
            return $this->redirectToRoute('add_table_format', ['tbl' => $timetable->getId()]);
        }
        $lesson_series = $this->get_lesson_series($tableformats, $timetable);
        $teachers_list = $this->teachers_list($teachers);
        list($items, $keys_string) = $this->add_items($lessons, $teachers_list);
        $timetablers = $this->em()->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $data['download'] = $existing_download;
        $data['timetablers'] = $timetablers;
        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_tableformats'] = $lesson_tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;
        $data['keys_string'] = $keys_string;

        return $this->render('timetable/tableformat.html.twig', $data);

    }
    /**
     * @Route("/timetable/class/{classId}", name="view_timetable_for_class")
     */
    public function classAction(Request $request, $classId)
    {
        ini_set('memory_limit', '-1');
        $data = [];

        $user = $this->find_user();
        $tableId = $request->query->get('tbl');
        $timetable = $this->find_timetable($tableId);
        $class = $this->find_class($classId);
        $tableformats = $this->find_table_formats($timetable);
        $lessons = $this->find_lessons($timetable);
        $teachers = $this->find_teachers($timetable);

        if(!$tableformats){
            $this->addFlash('error', 'Please setup the timetable order of events!');
            return $this->redirectToRoute('add_table_format', ['tbl' => $timetable->getId()]);
        }
        $lesson_series = $this->get_lesson_series($tableformats, $timetable);
        $teachers_list = $this->teachers_list($teachers);
        list($items, $keys_string) = $this->add_items($lessons, $teachers_list, "full_name");
        $timetablers = $this->em()->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $data['timetablers'] = $timetablers;
        $data['class'] = $class;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;
        $data['keys_string'] = $keys_string;

        return $this->render('timetable/class.html.twig', $data);

    }

    /**
     * @Route("/timetable/teacher/{teacherId}", name="view_timetable_single_teacher")
     */
    public function teacherAction(Request $request, $teacherId)
    {
        ini_set('memory_limit', '-1');
        $data = [];

        $user = $this->find_user();
        $tableId = $request->query->get('tbl');
        $timetable = $this->find_timetable($tableId);
        $classes = $this->find_classes($user, $timetable);
        $tableformats = $this->find_table_formats($timetable);
        $lessons = $this->find_lessons($timetable);
        $teacher = $this->find_teacher($teacherId);

        if(!$tableformats){
            $this->addFlash('error', 'Please setup the timetable order of events!');
            return $this->redirectToRoute('add_table_format', ['tbl' => $timetable->getId()]);
        }
        $lesson_series = $this->get_lesson_series($tableformats, $timetable);
        list($items, $dups) = $this->add_items_single_teacher($lessons, $teacher);
        $timetablers = $this->em()->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $data['teacher'] = $teacher;
        $data['timetablers'] = $timetablers;
        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;
        $data['dups'] = $dups;

        return $this->render('timetable/individual.html.twig', $data);

    }

    /**
     * @Route("/timetable/pdf/{teacherId}/{format}", name="download_teacher")
     */
    public function pdfSingleAction(Request $request, $teacherId, $format)
    {
        ini_set('memory_limit', '-1');
        $data = [];

        $user = $this->find_user();
        $tableId = $request->query->get('tbl');
        $timetable = $this->find_timetable($tableId);
        $classes = $this->find_classes($user, $timetable);
        $tableformats = $this->find_table_formats($timetable);
        $lessons = $this->find_lessons($timetable);
        $teacher = $this->find_teacher($teacherId);
        $teacherName = $teacher->getFullName();

        if(!$tableformats){
            $this->addFlash('error', 'Please setup the timetable order of events!');
            return $this->redirectToRoute('add_table_format', ['tbl' => $timetable->getId()]);
        }
        $lesson_series = $this->get_lesson_series($tableformats, $timetable);
        list($items, $dups) = $this->add_items_single_teacher($lessons, $teacher);
        $timetablers = $this->em()->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $data['teacher'] = $teacher;
        $data['timetablers'] = $timetablers;
        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;
        $data['dups'] = $dups;
        if($format == 'pdf'){
           // return $this->render('timetable/tableformat.html.twig', $data);
            $appPath = $this->container->getParameter('kernel.root_dir');

            $html = $this->renderView('pdf/timetable_single.html.twig', $data);

            $filename = sprintf("{$teacherName}_timetable-%s.pdf", date('Ymd~his'));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array('orientation'=>'Landscape')),
                200,
                [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
                ]
            );

        } else if($format == 'img') {
            $appPath = $this->container->getParameter('kernel.root_dir');

            $html = $this->renderView('pdf/timetable_single_img.html.twig', $data);

            $filename = sprintf("{$teacherName}_timetable-%s.jpg", date('Ymd~his'));

            return new Response(
                $this->get('knp_snappy.image')->getOutputFromHtml($html),
                200,
                [
                    'Content-Type'        => 'image/jpg',
                    'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
                ]
            );

        }
    }

    /**
     * @Route("/timetable/class/pdf/{classId}/{format}", name="download_class")
     */
    public function pdfClassAction(Request $request, $classId, $format)
    {
        ini_set('memory_limit', '-1');
        $data = [];

        $user = $this->find_user();
        $tableId = $request->query->get('tbl');
        $timetable = $this->find_timetable($tableId);
        $class = $this->find_class($classId);
        $tableformats = $this->find_table_formats($timetable);
        $lessons = $this->find_lessons($timetable);
        $teachers = $this->find_teachers($timetable);
        $className  = $class->getCTitle();

        if(!$tableformats){
            $this->addFlash('error', 'Please setup the timetable order of events!');
            return $this->redirectToRoute('add_table_format', ['tbl' => $timetable->getId()]);
        }
        $lesson_series = $this->get_lesson_series($tableformats, $timetable);
        $teachers_list = $this->teachers_list($teachers);
        list($items, $keys_string) = $this->add_items($lessons, $teachers_list, "full_name");
        $timetablers = $this->em()->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);

        $data['timetablers'] = $timetablers;
        $data['class'] = $class;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;
        $data['keys_string'] = $keys_string;

        if($format == 'pdf'){
           // return $this->render('timetable/tableformat.html.twig', $data);
            $appPath = $this->container->getParameter('kernel.root_dir');

            $html = $this->renderView('pdf/class_timetable.html.twig', $data);

            $filename = sprintf("class{$className}_timetable-%s.pdf", date('Ymd~his'));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array('orientation'=>'Landscape')),
                200,
                [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
                ]
            );

        } else if($format == 'img') {
            $appPath = $this->container->getParameter('kernel.root_dir');

            $html = $this->renderView('pdf/class_timetable.html.twig', $data);

            $filename = sprintf("class{$className}_timetable-%s.jpg", date('Ymd~his'));

            return new Response(
                $this->get('knp_snappy.image')->getOutputFromHtml($html),
                200,
                [
                    'Content-Type'        => 'image/jpg',
                    'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
                ]
            );

        }
    }

    /**
     * @Route("/timetable/pdf", name="download_master_pdf")
     */
    public function pdfAction(Request $request)
    {
        ini_set('memory_limit', '-1');
        $data = [];

        $user = $this->find_user();
        $tableId = $request->query->get('tbl');
        $timetable = $this->find_timetable($tableId);
        $classes = $this->find_classes($user, $timetable);
        $tableformats =  $this->find_table_formats($timetable);
        $lessons = $this->find_lessons($timetable);
        $lesson_series = $this->get_lesson_series($tableformats, $timetable);
        $teachers = $this->find_teachers($timetable);
        $teachers_list = $this->teachers_list($teachers);
        list($items, $keys_string) = $this->add_items($lessons, $teachers_list);
        $timetableName = $timetable->getTitle();

        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;
        $data['keys_string'] = $keys_string;

        // return $this->render('timetable/tableformat.html.twig', $data);
        $appPath = $this->container->getParameter('kernel.root_dir');

        $html = $this->renderView('pdf/timetable.html.twig', $data);

        $filename = sprintf("{$timetableName}-%s.pdf", date('Ymd~his'));

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
     * @Route("/timetable/image", name="download_master_img")
     */
    public function imageAction(Request $request)
    {
        ini_set('memory_limit', '-1');
        $data = [];

        $user = $this->find_user();
        $tableId = $request->query->get('tbl');
        $timetable = $this->find_timetable($tableId);
        $classes = $this->find_classes($user, $timetable);
        $tableformats =  $this->find_table_formats($timetable);
        $lessons = $this->find_lessons($timetable);
        $lesson_series = $this->get_lesson_series($tableformats, $timetable);
        $teachers = $this->find_teachers($timetable);
        $teachers_list = $this->teachers_list($teachers);
        list($items, $keys_string) = $this->add_items($lessons, $teachers_list);
        $timetableName = $timetable->getTitle();

        $data['classes'] = $classes;
        $data['timetable'] = $timetable;
        $data['tableformats'] = $tableformats;
        $data['lesson_series'] = $lesson_series;
        $data['actual_lessons'] = $lessons;
        $data['items'] = $items;
        $data['keys_string'] = $keys_string;

        // return $this->render('timetable/tableformat.html.twig', $data);
        $appPath = $this->container->getParameter('kernel.root_dir');

        $html = $this->renderView('pdf/timetable_img.html.twig', $data);

        $filename = sprintf("{$timetableName}-%s.jpg", date('Ymd~his'));

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


    private function add_minutes($minutes_to_add, $given_time){

        $time = new \DateTime($given_time);
        $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $new_time = $time->format('H:i');
        return $new_time;
    }

    private function find_user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    private function get_timetables($user){
        $timetables = $this->em()->getRepository('AppBundle:Timetable')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );
        return $timetables;
    }

    private function get_timetables_class($user, $class){
        $timetables = $this->em()->getRepository('AppBundle:Timetable')
            ->findBy(
                array('user' => $user, 'classs' => $class),
                array('fName' => 'ASC')
            );
        return $timetables;
    }

    private function find_timetable($timetableId){
        $timetable = $this->em()->getRepository('AppBundle:Timetable')
            ->find($timetableId);
        return $timetable;
    }

    private function find_class($classId){
        $class = $this->em()->getRepository('AppBundle:Classs')
            ->find($classId);
        return $class;
    }

    private function find_classes($user, $timetable){
        $classes = $this->em()->getRepository('AppBundle:Classs')
            ->findBy(
                array('user'=>$user, 'timetable'=>$timetable),
                array('id' => 'ASC')
            );
        return $classes;
    }

    private function find_table_formats($timetable){
        $tableformats = $this->em()->getRepository('AppBundle:TableFormat')
            ->findByTimetable($timetable);
        return $tableformats;
    }

    private function find_lessons($timetable){
        $lessons = $this->em()->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);
        return $lessons;
    }

    private function find_teachers($timetable){
        $teachers = $this->em()->getRepository('AppBundle:Teacher')
            ->findByTimetable($timetable);
        return $teachers;
    }

    private function find_teacher($id){
        $teacher = $this->em()->getRepository('AppBundle:Teacher')
            ->find($id);
        return $teacher;
    }

    private function add_items($lessons, $teachers_list, $what = "color"){
        $items = [];
        $keys_string = "";
        foreach($lessons as $lesson){
            $subjectEntity = $lesson->getSubject();
            $teacherEntity = $lesson->getTeacher();
            if($what == "color"){
                $asked_for = $teacherEntity->getColor();
            } else {
                $asked_for = $teacherEntity->getFName() ." ". $teacherEntity->getLName();
            }
            $items[][$lesson->getTableFormatColumn().".".$lesson->getClass().".".$lesson->getDay()] = $subjectEntity->getSTitle()."|".$asked_for."|".$teachers_list[$teacherEntity->getId()];
            $keys_string .= $lesson->getTableFormatColumn().".".$lesson->getClass().".".$lesson->getDay().":";
        }
        return [$items, $keys_string];
    }

    private function add_items_single_teacher($lessons, $teacher){
        $items = [];
        $dups = [];
        foreach($lessons as $lesson){
            $subjectEntity = $lesson->getSubject();
            $teacherEntity = $lesson->getTeacher();
            if($teacherEntity == $teacher){
                $key = $lesson->getTableFormatColumn().".".$lesson->getDay();
                if(array_key_exists($key, $items)){
                    $dups[][$key] = $subjectEntity->getSTitle()."|".$teacherEntity->getColor()."|".$teacher->getId()."|".$lesson->getClass();
                } else {
                    $items[$key] = $subjectEntity->getSTitle()."|".$teacherEntity->getColor()."|".$teacher->getId()."|".$lesson->getClass();
                }
            }

        }
        return [$items, $dups];
    }

    private function get_lesson_series($tableformats, $timetable){
        $lesson_series = [];
        $start_time = $timetable->getTime()->format('H:i');
        $current_time = $timetable->getTime()->format('H:i');
        foreach ($tableformats as $key => $value) {
            $first_part = $current_time;
            $current_time = $this->add_minutes($value->getDuration(), $current_time);
            $lesson_series[] = $first_part .'-'.$current_time.'|'.$value->getId();

        }
        return $lesson_series;
    }

    private function teachers_list($teachers){
        $teachers_list = [];
        foreach ($teachers as $key => $teacher) {
            $teachers_list[$teacher->getId()] = $teacher->getCode();
        }
        return $teachers_list;
    }

}
