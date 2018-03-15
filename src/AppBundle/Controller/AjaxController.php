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
    public function saveEntryAction(Request $request)
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
            $message = 'Teacher Class Conflict!';

            $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];

        } else {
            $totalInWeek = $em->getRepository('AppBundle:Timetabler')
                ->findBy(
                    array('subject' => $subj, 'class' => $class, 'timetable' => $timetable),
                    array('id' => 'DESC')
                );
            $countedLessons = count($totalInWeek);
            $message = $countedLessons + 1 . ' ' .$classSubject->getSubject()->getSTitle().' lessons this week, class '. $class->getCTitle();

            $subject = $classSubject->getSubject();

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $config = $em->getRepository('AppBundle:Config')
                ->findOneByUser($user);
            $maxAllowedOccurances = $config->getMaxLessonOccurances()-1;


            $subjectOccurancesToday = $em->getRepository('AppBundle:Timetabler')
                ->subjectOccurancesToday($subject, $class, $day);

            $numberOfOccurances = count($subjectOccurancesToday);
            if($numberOfOccurances > $maxAllowedOccurances){
                $message = 'Maximum Lessons for '. $subject->getSTitle() . ' reached!';

                $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
            } else {
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
                $timetabler->setTeacher($classSubject->getTeacher());
                $timetabler->setSubject($subject);
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
                $info = ['string'=>$string, 'color'=>$color, 'subject'=>$subject, 'occurances'=>$numberOfOccurances];                
                $arrData = ['output' => $info, 'theme' => 'dark', 'message' => $message ];
            }

                

        }

        return new JsonResponse($arrData);

    }

    /**
     * @Route("/remove/table/entry", name="remove_timetable_entry")
     */
    public function removeEntryAction(Request $request)
    {
        $fullId = explode('|',$request->request->get('info'));
        $em = $this->getDoctrine()->getManager();

        $day = explode("_", $fullId[0])[1];
        $class =  explode("_", $fullId[1])[1];
        $time =  explode("_", $fullId[2])[1];

        $timetablerEntry = $em->getRepository('AppBundle:Timetabler')
            ->findOneBy(
                array('day' => $day, 'class' => $class, 'time' => $time),
                array('id' => 'DESC')
            );
        $message = $timetablerEntry->getSubject()->getSTitle(). 'has been removed';
        $id = 'class-'.$timetablerEntry->getClass()->getCTitle().'_day-'.$day.'_tblfmt-'.$timetablerEntry->getTableFormatColumn();

        $arrData = ['message' => $message, 'id' => $id];
        
        $em->remove($timetablerEntry);
        $em->flush();

        return new JsonResponse($arrData);

    }

    /**
     * @Route("/timetable/hints", name="give_hints")
     */
    public function giveHintsAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $fullId = explode('_',$request->request->get('info'));
        $timetableId = $request->request->get('timetable');
        $em = $this->getDoctrine()->getManager();

        $className = explode("-", $fullId[0])[1];
        $day =  explode("-", $fullId[1])[1];
        $tableFormatColumn =  explode("-", $fullId[2])[1];

        $class = $em->getRepository('AppBundle:Classs')
            ->findOneBy(
                array('cTitle' => $className, 'user' => $user),
                array('id' => 'DESC')
            );

        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($timetableId);

        $subjects = $timetable->getSubjects();
        $teachers = $timetable->getTeachers();
        $classSubjects = $timetable->getClassSubjects();

        $timetablerEntriesForThisDayColumn = $em->getRepository('AppBundle:TimeTabler')
            ->findBy(
                array('day' => $day, 'tableFormatColumn' =>$tableFormatColumn),
                array('id' => 'DESC')
            );

        $timetablerEntriesForThisDayClass = $em->getRepository('AppBundle:TimeTabler')
            ->findBy(
                array('day' => $day, 'class' =>$class),
                array('id' => 'DESC')
            );

        $classSubjectsInPlace = [];
        $classSubjectTeachers = [];
        foreach($timetablerEntriesForThisDayColumn as $c_s_entry){
            $classSubjectsInPlace[] = $c_s_entry->getSubject()->getSTitle().'|'.$c_s_entry->getTeacher().'|'.$c_s_entry->getTeacher()->getColor();
            $classSubjectTeachers[] = $c_s_entry->getTeacher();
        }
        foreach($timetablerEntriesForThisDayClass as $c_s_entry){
            $classSubjectsInPlace[] = $c_s_entry->getSubject()->getSTitle().'|'.$c_s_entry->getTeacher().'|'.$c_s_entry->getTeacher()->getColor();
        }

        $availableClassSubjects = [];
        foreach($classSubjects as $classSubject){
            if($classSubject->getCClass()->getCTitle() == $className){
                $availableClassSubjects[] = $classSubject->getSubject()->getSTitle().'|'.$classSubject->getTeacher().'|'.$classSubject->getTeacher()->getColor();                
            }
            
        }

        $difference = array_diff($availableClassSubjects, $classSubjectsInPlace);
        
        $available = '<h4>Hints:</h4>';
        foreach($difference as $item){ 
            $color = explode("|", $item)[2];
            $teacher = explode("|", $item)[1];
            $subject = explode("|", $item)[0];
            if(!in_array(explode("|", $item)[1], $classSubjectTeachers)){
                $available .= '<span class="" style="background-color:'.$color.';">'.$subject.' ['.$teacher.']</span><br />';
            }
            
        }
        if($available == ""){
            $available = "No Hints";
        }

        return new JsonResponse($available);

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
        $maxLessonOccurances = $request->request->get('maxLessonOccurances');
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
        $config->setMaxLessonOccurances($maxLessonOccurances);
        $config->setFooterMessage($footerMessage);
        $config->setSidebar($sidebar);

        $em->persist($config);
        
        $em->flush();

        return new JsonResponse("success");

    }

}
