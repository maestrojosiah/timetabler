<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Timetabler;
use AppBundle\Entity\Config;
use AppBundle\Entity\Classs;
use AppBundle\Entity\Subject;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\ClassSubject;
use AppBundle\Entity\Timetable;
use AppBundle\Entity\TableFormat;


class AjaxController extends Controller
{

  /**
   * @Route("/timetabler/save/entity", name="save_entity")
   */
  public function saveAction(Request $request)
  {

      $user = $this->find_user();
      $entity = $request->request->get('entity');
      $return = $request->request->get('return');
      $fields = $request->request->get('fields');
      $timetable_id = $request->request->get('timetable');
      $values = $request->request->get('values');
      $timetable = $this->find('Timetable', $timetable_id);

      $fields_with_values = array_combine($fields, $values);

      switch ($entity) {
        case 'Classs':
          $this_entity = new Classs();
          break;
        case 'Teacher':
          $this_entity = new Teacher();
          break;
        case 'Subject':
          $this_entity = new Subject();
          break;
        case 'TableFormat':
          $this_entity = new TableFormat();
          break;
        case 'ClassSubject':
          $this_entity = new ClassSubject();
          $subj = $this->find('Subject', $fields_with_values['subject_id']);
          $classs = $this->find('Classs', $fields_with_values['class_id']);
          $teacher = $this->find('Teacher', $fields_with_values['teacher_id']);
          $this_entity->setCClass($classs);
          $this_entity->setTeacher($teacher);
          $this_entity->setSubject($subj);
          $to_return = $this_entity->getSubject()->getSTitle()." - [ Class ".$this_entity->getCClass()->getCTitle() ." ]";
          $isAlreadyCreated = $this->em()->getRepository('AppBundle:ClassSubject')
              ->isAlreadyCreated($subj, $classs);

          if($isAlreadyCreated){
              $to_return = "That subject is taken.";
              goto end;
          }

          break;
      }


      $this_entity->setUser($this->find_user());
      $this_entity->setTimetable($timetable);

      foreach ($fields_with_values as $key => $value) {
        if (strpos($key, '_id') == false) {
          $str = "set$key";
          $this_entity->$str($value);
        }
      }

      $this->save($this_entity);
      if($entity != 'ClassSubject'){
        $to_return = $this_entity->$return();
      }
      end:
      return new JsonResponse($to_return);
  }

  /**
   * @Route("/timetabler/edit/entity", name="edit_entity")
   */
  public function editAction(Request $request)
  {

      $user = $this->find_user();
      $entity = $request->request->get('entity');
      $id = $request->request->get('id');
      $fields = $request->request->get('fields');
      $values = $request->request->get('values');

      $fields_with_values = array_combine($fields, $values);
      $this_entity = $this->find($entity, $id);

      $this_entity->setUser($this->find_user());
      foreach ($fields_with_values as $key => $value) {
        if (strpos($key, '_id') == false) {
          $str = "set$key";
          $this_entity->$str($value);
        }
      }
      $this->save($this_entity);
      return new JsonResponse("true");
  }

    /**
     * @Route("/save/table/entry", name="save_timetable_entry")
     */
    public function saveEntryAction(Request $request)
    {
        $fullId = explode('|',$request->request->get('info'));
        $forced = $request->request->get('forced');
        $classSubj = explode("_", $fullId[0])[1];
        $subj =  explode("_", $fullId[1])[1];
        $time =  explode("_", $fullId[2])[1];
        $t_table =  explode("_", $fullId[3])[1];
        $day =  explode("_", $fullId[4])[1];
        $tableFormatColumn =  explode("_", $fullId[5])[1];

        $timetable = $this->find_timetable($t_table);
        $classSubject = $this->find_class_subject($classSubj);
        $class = $classSubject->getCClass();
        $teacher = $classSubject->getTeacher();

        list($message, $arrData) = $this->attempt_to_save_timetabler($day, $tableFormatColumn, $teacher, $classSubject, $classSubject->getSubject(), $class, $timetable, $subj, $time, $forced, "update", false);

        return new JsonResponse($arrData);

    }

    /**
     * @Route("/fix/table/entry", name="fix_timetable_entry")
     */
    public function fixEntryAction(Request $request)
    {
        $fullId = explode('|',$request->request->get('info'));


        $classSubj = $request->request->get('classSubj');
        $subj =  $request->request->get('subject');
        $teacherId =  $request->request->get('teacher');
        $forced =  $request->request->get('forced');
        $time =  explode("_", $fullId[4])[1];
        $t_table =  $request->request->get('timetable');
        $day =  explode("_", $fullId[2])[1];
        $tableFormatColumn =  explode("_", $fullId[3])[1];
        $classId =  explode("_", $fullId[1])[1];

        $timetable = $this->find_timetable($t_table);

        $class = $this->em()->getRepository('AppBundle:Classs') ->find($classId);
        $teacher = $this->em()->getRepository('AppBundle:Teacher') ->find($teacherId);
        $subject = $this->em()->getRepository('AppBundle:Subject') ->find($subj);
        $classSubject = $this->find_class_subject($classSubj);

        list($message, $arrData) = $this->attempt_to_save_timetabler($day, $tableFormatColumn, $teacher, $classSubject, $subject, $class, $timetable, $subj, $time, $forced, "update", false);

        return new JsonResponse($arrData);

    }

    /**
     * @Route("/delete/subjects/", name="delete_all_such_subjects")
     */
    public function delSubjectsAction(Request $request)
    {
        $fullId = $request->request->get('info');
        $subject_id = $request->request->get('subject_id');
        $table_id =  $request->request->get('timetable');

        $timetable = $this->find_timetable($table_id);

        $subject = $this->em()->getRepository('AppBundle:Subject')->find($subject_id);
        $timetabler_entries_of_this_subject = $this->em()->getRepository('AppBundle:Timetabler') -> findBySubject($subject);

        $message = "";

        foreach($timetabler_entries_of_this_subject as $entry){
            $this->em()->remove($entry);
            $this->em()->flush();
            $message = "Deleted Successfully";
        }

        return new JsonResponse($message);

    }

    /**
     * @Route("/remove/table/entry", name="remove_timetable_entry")
     */
    public function removeEntryAction(Request $request)
    {
        $fullId = explode('|',$request->request->get('info'));


        $day = explode("_", $fullId[0])[1];
        $class =  explode("_", $fullId[1])[1];
        $time =  explode("_", $fullId[2])[1];
        $tableFormat =  explode("_", $fullId[3])[1];

        $timetablerEntries = $this->em()->getRepository('AppBundle:Timetabler')
            ->findBy(
                array('day' => $day, 'class' => $class, 'tableFormatColumn' => $tableFormat),
                array('id' => 'DESC')
            );
        foreach($timetablerEntries as $timetablerEntry){
          $message = "Successfully remove subject(s)";
          $id = 'class-'.$timetablerEntry->getClass()->getCTitle().'_day-'.$day.'_tblfmt-'.$timetablerEntry->getTableFormatColumn();
          $this->em()->remove($timetablerEntry);
          $this->em()->flush();
        }

        $arrData = ['message' => $message, 'id' => $id];

        return new JsonResponse($arrData);

    }

    /**
     * @Route("/take/from_timetable", name="take_classes_from_this_timetable")
     */
    public function useTimetableAction(Request $request)
    {
        $this_timetableId = $request->request->get('this_timetable');
        $other_timetableId = $request->request->get('other_timetable');
        $entity = $request->request->get('entity');
        $user = $this->find_user();



        $this_timetable = $this->find_timetable($this_timetableId);

        $other_timetable = $this->find_timetable($other_timetableId);
        if($entity == 'Classs'){
            $entities = $other_timetable->getClasses();
            foreach($entities as $anEntity){
                $class = new Classs();

                $class->setTimetable($this_timetable);
                $class->setcTitle($anEntity->getCTitle());
                $class->setUser($anEntity->getUser());

                $this->save($class);

                $url = $this->generateUrl('homepage');
            }
        } else if ($entity == 'Teacher'){
            $entities = $other_timetable->getTeachers();
            foreach($entities as $anEntity){
                $teacher = new Teacher();

                $teacher->setTimetable($this_timetable);
                $teacher->setFName($anEntity->getFName());
                $teacher->setLName($anEntity->getLName());
                $teacher->setCode($anEntity->getCode());
                $teacher->setUser($anEntity->getUser());
                $teacher->setColor($anEntity->getColor());

                $this->save($teacher);

                $url = $this->generateUrl('homepage');
            }
        } else if ($entity == 'Subject'){
            $entities = $other_timetable->getSubjects();
            foreach($entities as $anEntity){
                $subject = new Subject();

                $subject->setTimetable($this_timetable);
                $subject->setUser($anEntity->getUser());
                $subject->setSTitle($anEntity->getSTitle());

                $this->save($subject);

                $url = $this->generateUrl('homepage');
            }
        } else if ($entity == 'TableFormat'){
            $entities = $other_timetable->getTableFormats();
            foreach($entities as $anEntity){
                $tableFormat = new TableFormat();

                $tableFormat->setTimetable($this_timetable);
                $tableFormat->setUser($anEntity->getUser());
                $tableFormat->setActivity($anEntity->getActivity());
                $tableFormat->setDuration($anEntity->getDuration());
                $tableFormat->setTitle($anEntity->getTitle());

                $this->save($tableFormat);

                $url = $this->generateUrl('homepage');
            }
        } else if ($entity == 'ClassSubject'){
            $entities = $other_timetable->getClassSubjects();
            foreach($entities as $anEntity){
                $classSubject = new ClassSubject();
                $teacher = $this->em()->getRepository('AppBundle:Teacher')
                    ->findOneBy(
                        array('user' => $user, 'timetable' => $this_timetable, 'lName' => $anEntity->getTeacher()->getLName(), 'color' => $anEntity->getTeacher()->getColor()),
                        array('id' => 'DESC')
                    );

                $class = $this->em()->getRepository('AppBundle:Classs')
                    ->findOneBy(
                        array('user' => $user, 'timetable' => $this_timetable, 'cTitle' => $anEntity->getCClass()->getCTitle()),
                        array('id' => 'DESC')
                    );

                $subject = $this->em()->getRepository('AppBundle:Subject')
                    ->findOneBy(
                        array('user' => $user, 'timetable' => $this_timetable, 'sTitle' => $anEntity->getSubject()->getSTitle()),
                        array('id' => 'DESC')
                    );

                $classSubject->setTimetable($this_timetable);
                $classSubject->setUser($anEntity->getUser());
                $classSubject->setCClass($class);
                $classSubject->setTeacher($teacher);
                $classSubject->setSubject($subject);

                $this->save($classSubject);

                $url = $this->generateUrl('homepage');
            }
        }

        $output = ['url' => $url];
        return new JsonResponse($output);

    }

    /**
     * @Route("/timetable/hints", name="give_hints")
     */
    public function giveHintsAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $fullId = explode('_',$request->request->get('info'));
        $timetableId = $request->request->get('timetable');

        $timetable = $this->find_timetable($timetableId);


        $className = explode("-", $fullId[0])[1];
        $day =  explode("-", $fullId[1])[1];
        $tableFormatColumn =  explode("-", $fullId[2])[1];

        $class = $this->em()->getRepository('AppBundle:Classs')
            ->findOneBy(
                array('timetable' => $timetable, 'cTitle' => $className, 'user' => $user),
                array('id' => 'DESC')
            );

        $subjects = $timetable->getSubjects();
        $teachers = $timetable->getTeachers();
        $classSubjects = $timetable->getClassSubjects();

        $timetablerEntriesForThisDayColumn = $this->em()->getRepository('AppBundle:TimeTabler')
            ->findBy(
                array('timetable' => $timetable, 'day' => $day, 'tableFormatColumn' =>$tableFormatColumn),
                array('id' => 'DESC')
            );

        $timetablerEntriesForThisDayClass = $this->em()->getRepository('AppBundle:TimeTabler')
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
                $available .= '<span class="" style="background-color:'.$color.'; color:black!important;">'.$subject.' ['.$teacher.']</span><br />';
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


        $id = $fullId[2];
        $teacher = $this->em()->getRepository('AppBundle:Teacher')
            ->find($id);

        $timetablers = $this->em()->getRepository('AppBundle:Timetabler')
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
                </div>
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


        $schoolTitle = $request->request->get('schoolTitle');
        $schoolAddress = $request->request->get('schoolAddress');
        $tableEntries = $request->request->get('tableEntries');
        $maxLessonOccurances = $request->request->get('maxLessonOccurances');
        $footerMessage = $request->request->get('footerMessage');
        $teacherNumbers = $request->request->get('teacherNumbers');
        $sidebar = $request->request->get('sidebar');
        $split_days = $request->request->get('split_days');

        $config = $this->get_config($user);
        if(!$config){
           $config = new Config();
        }

        $config->setUser($user);
        $config->setSchoolTitle($schoolTitle);
        $config->setSchoolAddress($schoolAddress);
        $config->setEntriesPerPage($tableEntries);
        $config->setMaxLessonOccurances($maxLessonOccurances);
        $config->setFooterMessage($footerMessage);
        $config->setTeacherNumbers($teacherNumbers);
        $config->setSidebar($sidebar);
        $config->setSplitDays($split_days);

        $this->save($config);

        return new JsonResponse("success");

    }

    /**
     * @Route("/timetable/clear", name="clear_timetable")
     */
    public function clearAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        $timetableId = $request->request->get('timetable');
        $timetable = $this->find_timetable($timetableId);

        $timetablers = $this->em()->getRepository('AppBundle:Timetabler')
            ->findByTimetable($timetable);


        foreach($timetablers as $timetabler){
            $this->em()->remove($timetabler);
        }


        $this->em()->flush();

        return new JsonResponse("success");

    }

    /**
     * @Route("/auto/entries", name="save_entries_auto")
     */
    public function saveEntriesAction(Request $request)
    {
        $user = $this->find_user();
        $message = [];
        $timetableId = $request->request->get('timetableId');
        $timetable = $this->find_timetable($timetableId);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $config = $this->get_config($user);
        $forced =  $request->request->get('forced');
        $tableFormats = $timetable->getTableFormats();
        $countTableFormats = count($tableFormats);
        $quantity = $request->request->get('auto_quantity');
        $subjectId = $request->request->get('auto_subject');
        $classId = $request->request->get('auto_class');
        $prefTime = $request->request->get('auto_time');
        $subject = $this->em()->getRepository('AppBundle:Subject')->find($subjectId);
        if($classId == "all"){
            $classes = $this->em()->getRepository('AppBundle:Classs')->findBy(
                array('user' => $user, 'timetable' => $timetable),
                array('id' => 'ASC')
            );
            $reload = 'true';
        } else {
            $classes = [$this->em()->getRepository('AppBundle:Classs')->find($classId)];
            $reload = 'true';
        }
        $countClasses = count($classes);
        $countDays = count($days);
        $totalToPlace = $quantity;
        $numberOfClasses = count($classes);
        $numberOfDays = count($days);
        $numberOfTableFormats = count($tableFormats);
        $days_assigned = $this->get_assigned_days($days, $quantity);
        $numberOfEntriesPossible = $quantity * count($classes);
        $shuffledTblFmt = $this->get_assigned_tblfmts($tableFormats, $prefTime);
        $classesArray = $this->to_a($classes);
        $message = [];
        $subjectCount  = 0;

        foreach($days_assigned as &$day){
            $message[] = "now on day {$day}...";
            $copyShuffledTblFmt = array_values($shuffledTblFmt);
            $copyShuffledTblFmtKeys = array_keys($shuffledTblFmt);
            $countCopyShuffledTblFmt = count($copyShuffledTblFmt)-1;
            $int = 0;
            $tries = 0;
            $subjectCount += 1;
            $possible_entries = 600;
            foreach($classes as $class){
                $message[] = "now on class ".$class->getCTitle()."...";
                $tries += 1;
                if($tries == $possible_entries){
                    $message[] = 'Maximum tries reached. couldn\'t find a slot!';
                    $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                    break 1;
                }

                list($lessonsAlreadyRecorded, $countLessonsAlreadyRecorded) = $this->recorded_lessons_class_subject($class, $subject, $timetable, $user);

                if($countLessonsAlreadyRecorded == $totalToPlace){
                    $message[] = 'Enough!';
                    $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                    $this->addFlash('error', 'Enough '.$subject->getSTitle().' lessons for class '. $class->getCTitle().'!');
                    continue;
                } else {
                    $classSubject = $this->em()->getRepository('AppBundle:ClassSubject')
                        ->findOneBy(
                            array('cClass' => $class, 'subject' => $subject, 'timetable' => $timetable, 'user' => $user),
                            array('id' => 'DESC')
                        );

                    if(!$classSubject){
                        $message[] = 'Please assign a teacher to this subject for class'.$class->getCTitle().'!';

                        $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                        $classesArray[] = $class;
                        $int += 2;
                        if($int > $countCopyShuffledTblFmt){
                            $int = 0;
                        }
                        break 1;

                    } else {
                        retry:
                        $teacher = $classSubject->getTeacher();
                        $random_table_format = array_rand($copyShuffledTblFmtKeys);
                        list($message, $arrData) = $this->attempt_to_save_timetabler($day, $copyShuffledTblFmt[$random_table_format], $teacher, $classSubject, $classSubject->getSubject(), $class, $timetable, $subject, 'auto', $forced, "new", true);
                        $test = "test";
                        if( empty($arrData['output']) ){
                            list($int, $subjectCount) = $this->cursor($days, $subjectCount, $int, $countCopyShuffledTblFmt, $countTableFormats);
                            $tries++;
                            $test = $totalToPlace;
                            if($tries < $possible_entries){
                                $message[] = "slot occupied... looking around...";
                                goto retry;
                            } else {
                                $message[] = "Out of ". $tries ." slots. Extend slots or reshuffle entries.";
                                $this->addFlash('error', 'Out of '. $tries .' slots. Please reshuffle your entries!');
                                continue;
                            }

                        } else {
                            $message[] = "found one... Going to next day...";
                            list($int, $subjectCount) = $this->cursor($days, $subjectCount, $int, $countCopyShuffledTblFmt, $countTableFormats);
                            $tries++;
                            $test = $totalToPlace;
                            continue 1;
                        }
                    }

                }
            }


        }

        end:
        $url = $this->generateUrl('view_timetable', ['tbl' => $timetable->getId()]);
        $toSend = ['reload' => $reload, 'message' => $message, 'url' => $url];
        return new JsonResponse($toSend);

    }

    public function cursor($days, $subjectCount, $int, $countCopyShuffledTblFmt, $countTableFormats){
        if($subjectCount > count($days)){
            $int += rand(2, $countCopyShuffledTblFmt);
        } else if($int >= $countCopyShuffledTblFmt){
            $int = 0;
        } else {
            $int += 1;
        }

        return [$int, $subjectCount];

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

    private function find_user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    private function find($entity, $id){
        $entity = $this->em()->getRepository("AppBundle:$entity")->find($id);
        return $entity;
    }

    private function find_timetable($timetableId){
        $timetable = $this->em()->getRepository('AppBundle:Timetable')
            ->find($timetableId);
        return $timetable;
    }

    private function find_class_subject($classSubj){
        $classSubject = $this->em()->getRepository('AppBundle:ClassSubject')
            ->find($classSubj);
        return $classSubject;
    }

    private function find_timetablers_subj_class_ttbl($subject, $class, $timetable, $order = 'DESC'){
        $timetablers = $this->em()->getRepository('AppBundle:Timetabler')
            ->findBy(
                array('subject' => $subject, 'class' => $class, 'timetable' => $timetable),
                array('id' => $order)
            );
        return $timetablers;
    }

    private function find_timetablers_subj_class_day($subject, $class, $day){
        $subjects = $this->em()->getRepository('AppBundle:Timetabler')
            ->subjectOccurancesToday($subject, $class, $day);
        return $subjects;
    }

    private function get_config($user){
        $config = $this->em()->getRepository('AppBundle:Config')
            ->findOneByUser($user);
        return $config;
    }

    private function get_or_create_timetabler($day, $class, $tableFormatColumn, $subject, $status, $auto=false){

        $recorded = $this->em()->getRepository('AppBundle:Timetabler')
            ->isAlreadyRecordedFormat($day, $class, $tableFormatColumn, $subject);
        $recorded_minimum = $this->em()->getRepository('AppBundle:Timetabler')
            ->isAlreadyRecordedFormatMin($day, $class, $tableFormatColumn);

        if($recorded_minimum && $auto == true){
          return NULL;
        } elseif($recorded) {

            if($status == "new"){
                return NULL;
            } else {
                $timetabler = $this->em()->getRepository('AppBundle:Timetabler')
                ->find($recorded->getId());
            }
        } else {
            $timetabler = new TimeTabler();
        }

        return $timetabler;
    }

    private function save($entity){
        $this->em()->persist($entity);
        $this->em()->flush();
    }

    private function is_clashing($day, $tableFormatColumn, $teacher){
        $record = $this->em()->getRepository('AppBundle:Timetabler')
            ->isClashingFormat($day, $tableFormatColumn, $teacher);
        return $record;
    }

    private function set_timetabler_values($timetabler, $classSubject, $user, $timetable, $time, $day, $classSubjectTeacher, $subject, $classSubjectClass, $tableFormatColumn){
        $timetabler->setClassSubject($classSubject);
        $timetabler->setUser($user);
        $timetabler->setTimetable($timetable);
        $timetabler->setTime($time);
        $timetabler->setDay($day);
        $timetabler->setTeacher($classSubjectTeacher);
        $timetabler->setSubject($subject);
        $timetabler->setClass($classSubjectClass);
        $timetabler->setTableFormatColumn($tableFormatColumn);
        return $timetabler;
    }

    private function attempt_to_save_timetabler($day, $tableFormatColumn, $teacher, $classSubject, $subject, $class, $timetable, $subj, $time, $forced, $status="update", $auto){
        $message = [];
        $message[] = "Attempting to save entry...";
        $isClashing = $this->is_clashing($day, $tableFormatColumn, $teacher);

        if($isClashing && $forced == 'false'){
            $message[] = 'Teacher Class Conflict!';
            $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
        } else {
            $totalInWeek = $this->find_timetablers_subj_class_ttbl($subj, $class, $timetable);
            $countedLessons = count($totalInWeek);
            $message[] = $countedLessons + 1 . ' ' .$subject->getSTitle().' lessons this week, class '. $class->getCTitle();

            $user = $this->find_user();
            $config = $this->get_config($user);
            $maxAllowedOccurances = $config->getMaxLessonOccurances()-1;
            $subjectOccurancesToday = $this->find_timetablers_subj_class_day($subject, $class, $day);
            $numberOfOccurances = count($subjectOccurancesToday);

            if($numberOfOccurances > $maxAllowedOccurances){
                $message[] = 'Maximum Lessons for '. $subject->getSTitle() . ' reached!';
                $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
            } else {
                $timetabler = $this->get_or_create_timetabler($day, $class, $tableFormatColumn, $subject, $status, $auto);
                if($timetabler == NULL){
                    $message[] = 'Maximum Lessons for '. $subject->getSTitle() . ' reached!';
                    $arrData = ['output' => [], 'theme' => 'light', 'message' => $message ];
                    return [$message, $arrData];
                }
                $timetabler = $this->set_timetabler_values($timetabler, $classSubject, $user, $timetable, $time, $day, $teacher, $subject, $class, $tableFormatColumn);
                $this->save($timetabler);

                $class = $timetabler->getClass();
                $day = $timetabler->getDay();
                $format = $timetabler->getTableFormatColumn();
                $color = $teacher->getColor();
                $sbj = $this->em()->getRepository('AppBundle:Subject')
                    ->find($subj);

                $config = $this->get_config($user);
                $show_code = $config->getTeacherNumbers();
                if($show_code == "visible"){
                  $subject = substr($sbj->getSTitle(), 0, 3).".[".$teacher->getCode()."]";
                } else {
                  $subject = $sbj->getSTitle();
                }



                $string = 'class-'.$class.'_day-'.$day.'_tblfmt-'.$format;
                $info = ['string'=>$string, 'color'=>$color, 'subject'=>$subject, 'occurances'=>$numberOfOccurances];
                $arrData = ['output' => $info, 'theme' => 'dark', 'message' => $message, 'forced' => $forced ];
            }

        }

        return [$message, $arrData];

    }

    private function get_assigned_days($days, $quantity){
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
        return $days_assigned;
    }

    private function get_assigned_tblfmts($tableFormats, $slot="freestyle"){
        if($slot != "freestyle"){
            $tFormats = $this->choose_slot_before($tableFormats, $slot);
        } else {
            $tFormats = $tableFormats;
        }
        $tblfmt_assigned = [];
        $shuffledTblFmt = [];
        foreach($tFormats as $tableFormat){
            if($tableFormat->getActivity() == "lesson"){
                $tblfmt_assigned[] = $tableFormat->getId();
            }

        }

        // shuffle($tblfmt_assigned)

        foreach($tblfmt_assigned as $tblfmt){
            $shuffledTblFmt[] = $this->em()->getRepository('AppBundle:tableFormat')
                ->find($tblfmt);
        }

        return $shuffledTblFmt;
    }

    private function choose_slot_before($tableFormats, $slot){
        $count = 0;
        $sections = [];
        foreach ($tableFormats as $format) {
            $count++;
            if($count == $slot){
                break;
            } else {
                $sections[] = $format;
            }
        }
        return $sections;

    }


    private function to_a($entities){
        $array = [];
        foreach($entities as $entity){
            $array[] = $entity;
        }
        return $array;
    }

    private function recorded_lessons_class_subject($class, $subject, $timetable, $user){
        $lessonsAlreadyRecorded = $this->em()->getRepository('AppBundle:Timetabler')
            ->findBy(
                array('class' => $class, 'subject' => $subject, 'timetable' => $timetable, 'user' => $user),
                array('id' => 'DESC')
            );

        $countLessonsAlreadyRecorded = count($lessonsAlreadyRecorded);
        return [$lessonsAlreadyRecorded, $countLessonsAlreadyRecorded];
    }

}
