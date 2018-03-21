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
        $forced = $request->request->get('forced');
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
            ->isClashingFormat($day, $tableFormatColumn, $teacher);
                
        if($isClashing && $forced == 'false'){
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
                    ->isAlreadyRecordedFormat($day, $class, $tableFormatColumn);

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
                $arrData = ['output' => $info, 'theme' => 'dark', 'message' => $message, 'forced' => $forced ];
            }

                

        }

        return new JsonResponse($arrData);

    }

    /**
     * @Route("/fix/table/entry", name="fix_timetable_entry")
     */
    public function fixEntryAction(Request $request)
    {
        $fullId = explode('|',$request->request->get('info'));
        $em = $this->getDoctrine()->getManager();

        $classSubj = $request->request->get('classSubj');
        $subj =  $request->request->get('subject');
        $teacherId =  $request->request->get('teacher');
        $forced =  $request->request->get('forced');
        $time =  explode("_", $fullId[4])[1]; 
        $t_table =  $request->request->get('timetable');
        $day =  explode("_", $fullId[2])[1];
        $tableFormatColumn =  explode("_", $fullId[3])[1];
        $classId =  explode("_", $fullId[1])[1];

        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($t_table);

        // $classSubject = $em->getRepository('AppBundle:ClassSubject')
        //     ->find($classSubj);
        $class = $em->getRepository('AppBundle:Classs')
            ->find($classId);

        $teacher = $em->getRepository('AppBundle:Teacher')
            ->find($teacherId);

        $subject = $em->getRepository('AppBundle:Subject')
            ->find($subj);

        // If there is another lesson for this day and this time for this teacher
        // then show a notification that this is wrong
        $isClashing = $em->getRepository('AppBundle:Timetabler')
            ->isClashingFormat($day, $tableFormatColumn, $teacher);

        $found = count($isClashing);
                
        if($isClashing && $forced == 'false'){
            $message = 'Teacher Class Conflict!';

            $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];

        } else {
            $totalInWeek = $em->getRepository('AppBundle:Timetabler')
                ->findBy(
                    array('subject' => $subj, 'class' => $class, 'timetable' => $timetable),
                    array('id' => 'DESC')
                );
            $countedLessons = count($totalInWeek);
            $message = $countedLessons + 1 . ' ' .$subject->getSTitle().' lessons this week, class '. $class->getCTitle();

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
                    ->isAlreadyRecordedFormat($day, $class, $tableFormatColumn);

                if($recorded){
                    $timetabler = $em->getRepository('AppBundle:Timetabler')
                        ->find($recorded->getId());
                } else {
                    $timetabler = new TimeTabler();
                }

                $timetabler->setClassSubject(NULL);
                $timetabler->setUser($user);
                $timetabler->setTimetable($timetable);
                $timetabler->setTime($time);
                $timetabler->setDay($day);
                $timetabler->setTeacher($teacher);
                $timetabler->setSubject($subject);
                $timetabler->setClass($class);
                $timetabler->setTableFormatColumn($tableFormatColumn);


                $em->persist($timetabler);
                $em->flush();

                $class = $timetabler->getClass();
                $day = $timetabler->getDay();
                $format = $timetabler->getTableFormatColumn();
                // $clr = $em->getRepository('AppBundle:Teacher')
                //     ->find()
                $color = $teacher->getColor();
                $sbj = $em->getRepository('AppBundle:Subject')
                    ->find($subj);
                $subject = $sbj->getSTitle();

                $string = 'class-'.$class.'_day-'.$day.'_tblfmt-'.$format;
                $info = ['string'=>$string, 'color'=>$color, 'subject'=>$subject, 'occurances'=>$numberOfOccurances];                
                $arrData = ['output' => $info, 'theme' => 'dark', 'message' => $message, 'forced' => $forced ];
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
        $tableFormat =  explode("_", $fullId[3])[1];

        $timetablerEntry = $em->getRepository('AppBundle:Timetabler')
            ->findOneBy(
                array('day' => $day, 'class' => $class, 'tableFormatColumn' => $tableFormat),
                array('id' => 'DESC')
            );
        $message = $timetablerEntry->getSubject()->getSTitle(). ' has been removed';
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
        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($timetableId);


        $className = explode("-", $fullId[0])[1];
        $day =  explode("-", $fullId[1])[1];
        $tableFormatColumn =  explode("-", $fullId[2])[1];

        $class = $em->getRepository('AppBundle:Classs')
            ->findOneBy(
                array('timetable' => $timetable, 'cTitle' => $className, 'user' => $user),
                array('id' => 'DESC')
            );

        $subjects = $timetable->getSubjects();
        $teachers = $timetable->getTeachers();
        $classSubjects = $timetable->getClassSubjects();

        $timetablerEntriesForThisDayColumn = $em->getRepository('AppBundle:TimeTabler')
            ->findBy(
                array('timetable' => $timetable, 'day' => $day, 'tableFormatColumn' =>$tableFormatColumn),
                array('id' => 'DESC')
            );

        $timetablerEntriesForThisDayClass = $em->getRepository('AppBundle:TimeTabler')
            ->findBy(
                array('timetable' => $timetable, 'day' => $day, 'class' =>$class),
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

    /**
     * @Route("/auto/entries", name="save_entries_auto")
     */
    public function saveEntriesAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $timetableId = $request->request->get('timetableId');
        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($timetableId);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        $tableFormats = $timetable->getTableFormats();
        $quantity = $request->request->get('auto_quantity');
        $subjectId = $request->request->get('auto_subject');
        $classId = $request->request->get('auto_class');

        $subject = $em->getRepository('AppBundle:Subject')
            ->find($subjectId);
        if($classId != "all"){
            $classes = [$em->getRepository('AppBundle:Classs')->find($classId)];            
        } else {
            $classes = $timetable->getClasses();
        }

        $numberOfClasses = count($classes);
        $numberOfDays = count($days);
        $numberOfTableFormats = count($tableFormats);
        
        $days_assigned = [];
        if($quantity == 5){
            $days_assigned = $days;
        } elseif ($quantity > 5) {
            $days_assigned = $days;
            $excess = $quantity - count($days);
            $extraDays = $this->generateRandomDays($excess);
            foreach($extraDays as $aDay){
                $days_assigned[] = $aDay;
            }
        } elseif ($quantity < 5) {
            $days_assigned = $days;
            $less = (int)$quantity;
            $difference = count($days) - $quantity;
            shuffle($days_assigned);
            for ($i=0; $i < $difference; $i++) { 
                array_pop($days_assigned);
            }
        }

        $tblfmt_assigned = [];
        foreach($tableFormats as $tableFormat){
            if($tableFormat->getActivity() == "lesson"){
                $tblfmt_assigned[] = $tableFormat->getId();
            }
            
        }

        // quantity x classes
        $numberOfEntriesPossible = $quantity * count($classes);

        // shuffle($tblfmt_assigned);
        $shuffledTblFmt = [];
        foreach($tblfmt_assigned as $tblfmt){
            $shuffledTblFmt[] = $em->getRepository('AppBundle:tableFormat')
                ->find($tblfmt);
        }


        $css_class_string = [];
        $possible_entries = [];

        $classesArray = [];
        foreach($classes as $class){
            $classesArray[] = $class;
        }
        $message = [];
        $subjectCount  = 0;

        foreach($days_assigned as &$day){
            $copyShuffledTblFmt = $shuffledTblFmt;
            $copyShuffledTblFmt = array_values($copyShuffledTblFmt);
            $int = 0;
            $tries = 0;
            $subjectCount += 1;
            foreach($classesArray as &$class){
                $tries += 1;
                if($tries == 200){
                    $message[] = 'Maximum tries reached. couldn\'t find a slot!';
                    $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                    break 2;
                }

                $lessonsAlreadyRecorded = $em->getRepository('AppBundle:Timetabler')
                    ->findBy(
                        array('class' => $class, 'subject' => $subject, 'timetable' => $timetable, 'user' => $user),
                        array('id' => 'DESC')
                    );
                $countLessonsAlreadyRecorded = count($lessonsAlreadyRecorded);
                if($countLessonsAlreadyRecorded == $quantity*count($classes)){
                    $message[] = 'Enough!';

                    $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                    break 2;
                } else {
                    $classSubject = $em->getRepository('AppBundle:ClassSubject')
                        ->findOneBy(
                            array('cClass' => $class, 'subject' => $subject, 'timetable' => $timetable, 'user' => $user),
                            array('id' => 'DESC')
                        );

                    if(!$classSubject){
                            $message[] = 'Please assign a teacher to this subject for class'.$class->getCTitle().'!';

                            $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                            $classesArray[] = $class;
                            $int += 2;
                            if($int > count($copyShuffledTblFmt)-1){
                                $int = 0;
                            } 
                            break 2;

                    } else {
                        $teacher = $classSubject->getTeacher();

                        $isClashing = $em->getRepository('AppBundle:Timetabler')
                            ->isClashingFormat($day, $copyShuffledTblFmt[$int]->getId(), $teacher);

                        $found = count($isClashing);
                                
                        if($isClashing){
                            $message[] = 'Teacher Class Conflict!' . $day . $class . $copyShuffledTblFmt[$int]->getId();

                            $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                            $classesArray[] = $class;

                            if($subjectCount > 5){
                                $int += rand(2, count($tableFormats));
                            } else {
                                $int += 1;
                            }
                            
                            if($int > count($copyShuffledTblFmt)-1){
                                $int = 0;
                            } 
                            continue;

                        } else {

                            $user = $this->get('security.token_storage')->getToken()->getUser();
                            $config = $em->getRepository('AppBundle:Config')
                                ->findOneByUser($user);
                            $maxAllowedOccurances = $config->getMaxLessonOccurances()-1;


                            $subjectOccurancesToday = $em->getRepository('AppBundle:Timetabler')
                                ->subjectOccurancesToday($subject, $class, $day);

                            $numberOfOccurances = count($subjectOccurancesToday);
                            if($numberOfOccurances > $maxAllowedOccurances){
                                $message[] = 'Maximum Lessons for '. $subject->getSTitle() .' class'.$class->getCTitle(). ' reached! '.$day;
                                $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                                // $days[] = $day;
                                break 1;
                            } else {
                                $recorded = $em->getRepository('AppBundle:Timetabler')
                                    ->isAlreadyRecordedFormat($day, $class, $copyShuffledTblFmt[$int]);

                                if($recorded){
                                $message[] = 'There is already a record in this slot'.$day.$class.$copyShuffledTblFmt[$int]->getId();
                                $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                                    $classesArray[] = $class;
                                    $int += 1;
                                    if($int > count($copyShuffledTblFmt)-1){
                                        $int = 0;
                                    } 
                                    continue;
                                } else {
                                    $timetabler = new TimeTabler();
                                    $timetabler->setClassSubject($classSubject);
                                    $timetabler->setUser($user);
                                    $timetabler->setTimetable($timetable);
                                    $timetabler->setTime('auto');
                                    $timetabler->setDay($day);
                                    $timetabler->setTeacher($classSubject->getTeacher());
                                    $timetabler->setSubject($subject);
                                    $timetabler->setClass($classSubject->getCClass());
                                    $timetabler->setTableFormatColumn($copyShuffledTblFmt[$int]->getId());

                                    $em->persist($timetabler);
                                    $em->flush();
                                    // unset($copyShuffledTblFmt[$int]);
                                    // $copyShuffledTblFmt = array_values($copyShuffledTblFmt);

                                    $totalInWeek = $em->getRepository('AppBundle:Timetabler')
                                        ->findBy(
                                            array('subject' => $subject, 'class' => $class, 'timetable' => $timetable),
                                            array('id' => 'DESC')
                                        );
                                    $countedLessons = count($totalInWeek);
                                    $message[] = $countedLessons . ' ' .$subject->getSTitle().' lessons this week, class '. $class->getCTitle().' column '.$copyShuffledTblFmt[$int]->getId().' '. $day;

                                    $class = $timetabler->getClass();
                                    $day = $timetabler->getDay();
                                    $format = $timetabler->getTableFormatColumn();
                                    // $clr = $em->getRepository('AppBundle:Teacher')
                                    //     ->find()
                                    $color = $teacher->getColor();
                                    $subj = $subject->getSTitle();

                                    $string = 'class-'.$class.'_day-'.$day.'_tblfmt-'.$format;
                                    $info = ['string'=>$string, 'color'=>$color, 'subject'=>$subj, 'occurances'=>$numberOfOccurances];                
                                    $arrData = ['output' => $info, 'theme' => 'dark', 'message' => $message ];
                                    $int += 2;
                                    if($int > count($copyShuffledTblFmt)-1){
                                        $int = 0;
                                    } 
                                    continue 2;
                                }

                            }

                                

                        }

                    }

                }
            }
            

        }

        
        return new JsonResponse($message);

    }

    public function randomAssignAction($lesson, $quantity, $class){

    }

    public function generateRandomDays($quantity){
        $addedDays = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        for ($i=0; $i < $quantity; $i++) { 
            shuffle($days);
            $addedDays[] = array_pop($days);
        }
        return $addedDays;
    }

    public function addToArray($array, $item){
        array_push($array, $item);
    }

}
