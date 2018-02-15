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

        $teachers = $em->getRepository('AppBundle:Teacher')
        	->countTeachers($user);
        $data['countTeachers'] = $teachers;

        $timetables = $em->getRepository('AppBundle:Timetable')
        	->countTimetables($user);
        $data['countTimetables'] = $timetables;

        $subjects = $em->getRepository('AppBundle:Subject')
            ->countSubjects($user);
        $data['countSubjects'] = $subjects;

        // $exams = $em->getRepository('AppBundle:Exam')
        //     ->countExams($user);
        // $data['countExams'] = $exams;

        // $config = $em->getRepository('AppBundle:Config')
        // 	->findBy(
        //         array('user' => $user),
        //         array('id' => 'DESC')
        //     );
        // $data['config'] = $config;

        if($timetables == 0){
            $this->addFlash(
                'success',
                'Please add at least one class!'
            );
            return $this->redirectToRoute('add_timetable');
        }

        if($teachers == 0){
            $this->addFlash(
                'success',
                'Please add at least one student!'
            );
            return $this->redirectToRoute('add_teacher');
        }

        if($subjects == 0){
            $this->addFlash(
                'success',
                'Please add at least one subject!'
            );
            return $this->redirectToRoute('choose_table_for_subject');
        }

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
