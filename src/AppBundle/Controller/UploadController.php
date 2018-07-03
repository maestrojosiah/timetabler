<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use AppBundle\Form\UploadType;
use AppBundle\Entity\Upload;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class UploadController extends Controller
{
    /**
     * @Route("/upload", name="students_upload")
     */
    public function uploadAction(Request $request){
        ini_set('memory_limit', '-1');
        $data = [];
        $upload = new Upload();
        $class_id = $request->query->get('classId');
        $classs = $this->find('Classs', $class_id);

        $form = $this->createForm(UploadType::class, $upload);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $excelFile = $form->get('file')->getData();
            $originalName = $excelFile->getClientOriginalName();
        	$filepath = $this->get('kernel')->getProjectDir()."/web/excelFiles/";
            $excelFile->move($filepath, $originalName);
            $upload->setFile($originalName);
            $rows = $this->readThrough($originalName);
            $data['rows'] = $rows;
            if($this->addToDatabase($rows, $classs)){
                $this->save($upload);
                return $this->redirectToRoute('list_students', ['classId' => $class_id]);
            } else {
                $this->addFlash('error', "There is nothing in that excel file. Please add students with column A: name, column B: gender");
                return $this->redirectToRoute('students_upload');
            }

        }

        return $this->render('upload/upload.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
        ]);
    }

	/**
	 * @Route("/download", name="download_file")
	**/
	public function downloadFileAction(){
        $filepath = $this->get('kernel')->getProjectDir()."/web/excelFiles/example.xlsx";
	    $response = new BinaryFileResponse($filepath);
	    $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'example.xls');
	    return $response;
	}

    private function readThrough($filename){
    	// path to file
        $file = $this->get('kernel')->getProjectDir()."/web/excelFiles/$filename";
        // create a reader
		$reader  = $this->get('phpoffice.spreadsheet')->createReader('Xlsx');
		// load the spreadsheet to a variable
		$spreadsheet = $reader->load($file);
		// take data from spreadsheet and make array with it

		$reader->setReadDataOnly(TRUE);
		$worksheet = $spreadsheet->getActiveSheet();

		$rows = [];
		foreach ($worksheet->getRowIterator() as $row) {
		    $cellIterator = $row->getCellIterator();
		    $cellIterator->setIterateOnlyExistingCells(FALSE);

		    $col = [];
		    foreach ($cellIterator as $cell) {
		    	if($cell->getValue() != NULL && $cell->getValue() != ""){
		    		$col[] = $cell->getValue();
		    	}

		    }

		    $rows[] = $col;
		}

		return $rows;

    }

    private function addToDatabase($rows, $class){
    	if(isset($rows[0][0]) ){
            foreach($rows as $row){
        		$contact = new Student;
        		$contact->setNames($row[0]);
        		$contact->setGender($row[1]);
        		$contact->setClass($class);
        		$contact->setUser($this->user());
        		$this->save($contact);
    	    }
            return true;
        } else {
            return false;
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

}
