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

        $configs = $em->getRepository('AppBundle:Config')
        	->countConfigs($user);
        $data['countConfigs'] = $configs;

        $timetables = $em->getRepository('AppBundle:Timetable')
        	->countTimetables($user);
        $data['countTimetables'] = $timetables;

        $lastTimeTable = $em->getRepository('AppBundle:Timetable')
            ->findBy(
                array('user'=>$user),
                array('id' => 'DESC'),
                1
            );

        $lesson_tableformats = $em->getRepository('AppBundle:TableFormat')
              ->countPossibleLessons($user, $timetables[0]);
        $data['lesson_tableformats'] = $lesson_tableformats;

        $allTimetables = $em->getRepository('AppBundle:Timetable')
            ->findBy(
                array('user' => $user),
                array('id' => 'DESC'),
                10
            );

        $data['allTimetables'] = $allTimetables;

        if($configs == 0){
            $this->addFlash(
                'success',
                'Please add some settings!'
            );
            return $this->redirectToRoute('view_profile');
        }

        if($timetables == 0){
            $this->addFlash(
                'success',
                'Please add at least one timetable!'
            );
            return $this->redirectToRoute('add_timetable');
        }

        if(count($lastTimeTable[0]->getClasses()) == 0){
            $this->addFlash(
                'success',
                'Please add at least one class!'
            );
            return $this->redirectToRoute('add_classs', ['tbl' => $lastTimeTable[0]->getId()]);
        }

        if(count($lastTimeTable[0]->getTeachers()) == 0){
            $this->addFlash(
                'success',
                'Please add at least one teacher!'
            );
            return $this->redirectToRoute('add_teacher', ['tbl' => $lastTimeTable[0]->getId()]);
        }

        if(count($lastTimeTable[0]->getSubjects()) == 0){
            $this->addFlash(
                'success',
                'Please add at least one subject!'
            );
            return $this->redirectToRoute('add_subject', ['tbl' => $lastTimeTable[0]->getId()]);
        }

        if(count($lastTimeTable[0]->getTableFormats()) == 0){
            $this->addFlash(
                'success',
                'Please add timetable format!'
            );
            return $this->redirectToRoute('add_table_format', ['tbl' => $lastTimeTable[0]->getId()]);
        }

        return $this->render('default/index.html.twig', $data);
     }
}
