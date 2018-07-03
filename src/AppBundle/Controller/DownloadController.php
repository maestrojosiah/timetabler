<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DownloadController extends Controller
{
    /**
     * @Route("/downloads", name="my_downloads")
     */
    public function myDownloadsAction()
    {
    	$user = $this->user();
    	$data = [];
    	$downloads = $this->em()->getRepository('AppBundle:Download')
    		->findBy(
    			array('user' => $user),
    			array('id' => 'DESC'),
    			20
    		);
    	$data['downloads'] = $downloads;

      return $this->render('AppBundle:Download:my_downloads.html.twig', $data);
    }

    /**
     * @Route("/downloads/{timetable_id}", name="timetable_downloads")
     */
    public function tblDownloadsAction(Request $request, $timetable_id)
    {
    	$user = $this->user();
    	$data = [];
      $timetable = $this->find('Timetable', $timetable_id);
      $download_id = $request->query->get('a');
    	$download = $this->find('Download', $download_id);
    	$data['download'] = $download;
    	$data['timetable'] = $timetable;

      return $this->render('AppBundle:Download:timetable_downloads.html.twig', $data);
    }

    /**
     * @Route("/downloads/delete/{downloadId}", name="delete_download")
     */
    public function deleteAction(Request $request, $downloadId)
    {
    	$download = $this->em()->getRepository('AppBundle:Download')->find($downloadId);
    	$this->delete($download);
        return $this->redirectToRoute('my_downloads');
    }

    /**
     * @Route("/download/{what}", name="download")
     */
    public function downloadItAction(Request $request, $what){
    	$user = $this->user();
    	$string = "download_$what";
      $downloadId = $request->query->get('a');
      $timetable_id = $request->query->get('b');
    	$download = $this->em()->getRepository('AppBundle:Download')->find($downloadId);
      if($string == 'download_class'){
        $class_id = $request->query->get('ci');
        $format = $request->query->get('f');
        $url = $this->generateUrl($string, ['tbl' => $timetable_id, 'classId' => $class_id, 'format' => $format]) ;
      } else if ($string == 'download_teacher') {
        $teacher_id = $request->query->get('ti');
        $format = $request->query->get('f');
        $url = $this->generateUrl($string, ['tbl' => $timetable_id, 'teacherId' => $teacher_id, 'format' => $format]) ;
      } else {
        $url = $this->generateUrl($string, ['tbl' => $timetable_id, 'a' => $downloadId]) ;
      }
    	$data = [];
    	$downloads = $this->em()->getRepository('AppBundle:Download')
    		->findBy(
    			array('user' => $user),
    			array('id' => 'DESC')
    		);
    	$data['downloads'] = $downloads;
    	if($download->getTimes() < 1){
    		$download->setStatus(0);
    		$this->save($download);
    		return $this->render('AppBundle:Download:my_downloads.html.twig', $data);
    	} else {
    		$times = $download->getTimes();
    		if($times == 0){
    			$download->setStatus(0);
    			$active = false;
    		} else if ($times == 1){
          $download->setStatus(0);
          $active = true;
        } else {
    			$active = true;
    		}
    		if($active == true){
	    		$remaining_downloads = $times;
	    		$new_remaining_downloads = $remaining_downloads - 1;
	    		$download->setTimes($new_remaining_downloads);
	    		$this->save($download);
		    	$response = new RedirectResponse($url);
				  return $response;
    		} else {
          $data['active'] = $active;
    			return $this->render('AppBundle:Download:my_downloads.html.twig', $data);
    		}
    	}
    }

    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    private function save($entity){
        $this->em()->persist($entity);
        $this->em()->flush();
    }

    private function find($entity, $id){
        $entity = $this->em()->getRepository("AppBundle:$entity")->find($id);
        return $entity;
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

    private function delete($entity){
        $this->em()->remove($entity);
        $this->em()->flush();
        return true;
    }

}
