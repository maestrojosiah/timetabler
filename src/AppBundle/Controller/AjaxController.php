<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Timetabler;
use AppBundle\Entity\Config;
// use AppBundle\Entity\Stock;


class AjaxController extends Controller
{

    /**
     * @Route("/save/table/entry", name="save_timetable_entry")
     */
    public function getIdAction(Request $request)
    {
        $fullId = explode('|',$request->request->get('info'));
        $em = $this->getDoctrine()->getManager();

        $classSubj = explode("_", $fullId[0])[1];
        $subj =  explode("_", $fullId[1])[1];
        $time =  explode("_", $fullId[2])[1];
        $t_table =  explode("_", $fullId[3])[1];
        $day =  explode("_", $fullId[4])[1];
        $tableFormatColumn =  explode("_", $fullId[5])[1];

        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($t_table);

        $classSubject = $em->getRepository('AppBundle:ClassSubject')
            ->find($classSubj);
        $class = $classSubject->getCClass();
        $teacher = $classSubject->getTeacher();

        // If there is another lesson for this day and this time for this teacher
        // then show a notification that this is wrong
        $isClashing = $em->getRepository('AppBundle:Timetabler')
            ->isClashing($day, $time, $teacher);
                
        if($isClashing){
            $message = '<div class="alert alert-danger alert-dismissible" style="width:300px; float:right; height:50px; font-size:14;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-ban"></i> Alert!
                    Teacher Class Conflict!
                  </div>';
        } else {
            $message = '<div class="alert alert-success alert-dismissible" style="width:300px; float:right; height:50px; font-size:14;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i>
                Successfully Added '.$classSubject->getSubject()->getSTitle().'!.
              </div>';
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $recorded = $em->getRepository('AppBundle:Timetabler')
            ->isAlreadyRecorded($day, $class, $time);

        if($recorded){
            $timetabler = $em->getRepository('AppBundle:Timetabler')
                ->find($recorded->getId());
        } else {
            $timetabler = new TimeTabler();
        }

        $timetabler->setClassSubject($classSubject);
        $timetabler->setUser($user);
        $timetabler->setTimetable($timetable);
        $timetabler->setTime($time);
        $timetabler->setDay($day);
        $timetabler->setTeacher($classSubject->getTeacher()->getId());
        $timetabler->setSubject($subj);
        $timetabler->setClass($classSubject->getCClass());
        $timetabler->setTableFormatColumn($tableFormatColumn);


        $em->persist($timetabler);
        $em->flush();

        $class = $timetabler->getClass();
        $day = $timetabler->getDay();
        $format = $timetabler->getTableFormatColumn();
        // $clr = $em->getRepository('AppBundle:Teacher')
        //     ->find()
        $color = $classSubject->getTeacher()->getColor();
        $sbj = $em->getRepository('AppBundle:Subject')
            ->find($subj);
        $subject = $sbj->getSTitle();

        $string = 'class-'.$class.'_day-'.$day.'_tblfmt-'.$format;
        $info = ['string'=>$string, 'color'=>$color, 'subject'=>$subject];
        $arrData = ['output' => $info , 'message' => $message ];
        return new JsonResponse($arrData);

    }

    /**
     * @Route("/show/teacher/details", name="show_teacher_details")
     */
    public function getTeacherDetails(Request $request)
    {
        $fullId = explode('-',$request->request->get('info'));
        $em = $this->getDoctrine()->getManager();

        $id = $fullId[2];
        $teacher = $em->getRepository('AppBundle:Teacher')
            ->find($id);

        $timetablers = $em->getRepository('AppBundle:Timetabler')
            ->findBy(
                array('teacher' => $teacher->getId()),
                array('id' => 'DESC')
            );

        $lessons = count($timetablers);
        $classSubjects = $teacher->getClassSubjects();
        $c_s = [];
        foreach($classSubjects as $classSubject){
            $c_s[] = $classSubject->getCClass()->getCTitle() . "|" . $classSubject->getSubject()->getSTitle() ;
        }

        $html = '
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Total Lessons Per Week - '.$lessons.'</h3>
                <div class="box-body no-padding">
                  <table class="table">
                    <tbody>
                    <tr>
                      <th>Class</th>
                      <th>Subject</th>
                    </tr>';
                    foreach($classSubjects as $classSubject){
                    $html .= '<tr>
                      <td>'. $classSubject->getCClass()->getCTitle() .'</th>
                      <td>'. $classSubject->getSubject()->getSTitle() .'</th>
                    </tr>';
                    }
                    $html .= '</tbody>
                  </table>
                </div>
                <!-- /.box-body -->
              </div>
        ';

        $output = [$id, $html];

        return new JsonResponse($output);

    }

    /**
     * @Route("/settings/save", name="save_settings")
     */
    public function saveSettingsAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $schoolTitle = $request->request->get('schoolTitle');
        $schoolAddress = $request->request->get('schoolAddress');
        $tableEntries = $request->request->get('tableEntries');
        $footerMessage = $request->request->get('footerMessage');
        $sidebar = $request->request->get('sidebar');

        $config = $em->getRepository('AppBundle:Config')
            ->findOneByUser($user);
        if(!$config){
           $config = new Config();
        }
        
        $config->setUser($user);
        $config->setSchoolTitle($schoolTitle);
        $config->setSchoolAddress($schoolAddress);
        $config->setEntriesPerPage($tableEntries);
        $config->setFooterMessage($footerMessage);
        $config->setSidebar($sidebar);

        $em->persist($config);
        
        $em->flush();

        return new JsonResponse("success");

    }

}
