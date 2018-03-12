<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $tableId = $request->query->get('tbl');
        $data['tableId'] = $tableId;

        $teachers = $em->getRepository('AppBundle:Teacher')
        	->countTeachers($user);
        $data['countTeachers'] = $teachers;

        $timetables = $em->getRepository('AppBundle:Timetable')
        	->countTimetables($user);
        $data['countTimetables'] = $timetables;

        $lastTimeTable = $em->getRepository('AppBundle:Timetable')
            ->findBy(
                array('user'=>$user),
                array('id' => 'DESC'),
                1
            );
        // lessons * classes * days of week
        $subjects = $em->getRepository('AppBundle:Subject')
            ->countSubjects($user);
        $data['countSubjects'] = $subjects;
        $classes = $em->getRepository('AppBundle:Classs')
            ->countClasses($user);
        $data['countClasses'] = $classes;
        $allTimetables = $em->getRepository('AppBundle:Timetable')
            ->findBy(
                array('user' => $user),
                array('id' => 'DESC') 
            );

        $tableformats = $em->getRepository('AppBundle:TableFormat')
            ->countTableFormats($user);

        $tables = [];
        foreach($tableformats as $tableformat){
            $tables[] = $tableformat->getTimeTable()->getId();
        }
        $vals = array_count_values($tables);
        $data['countTableFormats'] = count($vals);
        $data['allTimetables'] = $allTimetables;


        if($timetables == 0){
            $this->addFlash(
                'success',
                'Please add at least one timetable!'
            );
            return $this->redirectToRoute('add_timetable');
        }

        if($classes == 0){
            $this->addFlash(
                'success',
                'Please add at least one class!'
            );
            return $this->redirectToRoute('add_class', ['tbl' => $lastTimeTable[0]->getId()]);
        }

        if($teachers == 0){
            $this->addFlash(
                'success',
                'Please add at least one teacher!'
            );
            return $this->redirectToRoute('add_teacher', ['tbl' => $lastTimeTable[0]->getId()]);
        }

        if($subjects == 0){
            $this->addFlash(
                'success',
                'Please add at least one subject!'
            );
            return $this->redirectToRoute('choose_table_for_subject');
        }

        if($tableformats == 0){
            $this->addFlash(
                'success',
                'Please add timetable format!'
            );
            return $this->redirectToRoute('add_table_format', ['tbl' => $lastTimeTable[0]->getId()]);
        }

        // foreach($allTimetables as $singleTimetable){
            
        // }
        // if(!$config){
        //     $this->addFlash(
        //         'success',
        //         'Some few more settings! You will need this in your documents'
        //     );
        //     return $this->redirectToRoute('change_settings');
        // }


        return $this->render('default/index.html.twig', $data);
     }
}
