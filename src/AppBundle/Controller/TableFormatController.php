<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TableFormat;
use AppBundle\Form\TableFormatType;

//issue: deletion
class TableFormatController extends Controller
{
    /**
     * @Route("/tableFormat/create", name="add_table_format")
     */
    public function createAction(Request $request)
    {
    	$data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;
        $data['form'] = [];

        $em = $this->getDoctrine()->getManager();

        $tableFormat = new TableFormat();
        $tableFormat->setUser($user);

        $tbl = $request->query->get('tbl');
        $teacher = $em->getRepository('AppBundle:Teacher')
            ->find($tbl);

        $timetable = $em->getRepository('AppBundle:timetable')
            ->find($teacher->getTimetable()->getId());

        $data['teacher'] = $teacher;
        $data['timetable'] = $timetable;
        $recordedRange = $timetable->getClassRange();
        $separate = explode('-', $recordedRange);

        $classes = range($separate[0], $separate[1]);
        $data['classes'] = $classes;

        $form = $this->createFormBuilder()
            ->add('title')
            ->add('activity')
            ->add('duration')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;

            $tableFormat->setTitle($form_data['title']);
            $tableFormat->setDuration($form_data['duration']);
            $tableFormat->setActivity($form_data['activity']);
            $tableFormat->setTimetable($timetable);

            $em->persist($tableFormat);
            $em->flush();

        	$this->addFlash(
	            'success',
	            'TableFormat created successfully!'
        	);

            return $this->redirectToRoute('add_table_format', ['tbl' => $tbl]);
		} else {
            $data['form'] = $form;
        }

	
        // replace this example code with whatever you need
        return $this->render('tableFormat/create.html.twig', $data );


    }

    /**
     * @Route("/timetable/format/choose", name="choose_table_for_format")
     */
    public function chooseAction(Request $request)
    {

        return $this->render('tableFormat/choose.html.twig');

    }



    /**
     * @Route("/tableFormat/list", name="list_tableFormats")
     */
    public function listAction(Request $request)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        $em = $this->getDoctrine()->getManager();

        $tableFormats = $em->getRepository('AppBundle:TableFormat')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );

        $tbl = $request->query->get('tbl');
        $timetable = $em->getRepository('AppBundle:timetable')
            ->find($tbl);

        $data['tableFormats'] = $tableFormats;
        $data['timetable'] = $timetable;

        return $this->render('tableFormat/list.html.twig', $data );

    }

    /**
     * @Route("/tableFormat/edit/{tableFormatId}", name="edit_tableFormat")
     */
    public function editAction(Request $request, $tableFormatId)
    {
        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;
        $data['form'] = [];

        $em = $this->getDoctrine()->getManager();

        $tableFormat = $em->getRepository('AppBundle:TableFormat')
            ->find($tableFormatId);

        $tbl = $request->query->get('tbl');

        $timetable = $em->getRepository('AppBundle:Timetable')
            ->find($tbl);

        $recordedRange = $timetable->getClassRange();
        $separate = explode('-', $recordedRange);

        $classes = range($separate[0], $separate[1]);
        $data['classes'] = $classes;

        $form = $this->createFormBuilder()
            ->add('title')
            ->add('activity')
            ->add('duration')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;

            $tableFormat->setTitle($form_data['title']);
            $tableFormat->setDuration($form_data['duration']);
            $tableFormat->setActivity($form_data['activity']);
            $tableFormat->setTimetable($timetable);

            $em->persist($tableFormat);
            $em->flush();

            $this->addFlash(
                'success',
                'TableFormat created successfully!'
            );

            return $this->redirectToRoute('list_tableFormats', ['tbl' => $tbl]);
        } else {
            
            $table_data['title'] = $tableFormat->getTitle();
            $table_data['activity'] = $tableFormat->getActivity();
            $table_data['duration'] = $tableFormat->getDuration();

            $data['form'] = $table_data;
        }

    
        // replace this example code with whatever you need
        return $this->render('tableFormat/edit.html.twig', $data );

    }


    /**
     * @Route("/tableFormat/delete/{tableFormatId}", name="delete_tableFormat")
     */
    public function deleteAction(Request $request, $tableFormatId)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $tableFormat = $em->getRepository('AppBundle:TableFormat')
            ->find($tableFormatId);

        $em->remove($tableFormat);
        $em->flush();

        return $this->redirectToRoute('list_tableFormat');

    }


}