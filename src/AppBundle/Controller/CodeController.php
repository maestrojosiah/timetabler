<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Code;
use AppBundle\Entity\Download;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class CodeController extends Controller
{

    /**
     * @Route("/code/generate/{qty}", name="generate_codes")
     */
    public function feedAction(Request $request, $qty){
        $data = [];

        // uncomment lines below to add codes to database
        for ($i=0; $i < $qty; $i++) {
            $randomString = $this->getRandStr();
            $code = new Code();
            $code->setRandomCode($randomString);
            $code->setStatus('virgin');
            $data[] = $randomString;
            $this->save($code);
        }

        // display 5 random codes if exists in database.
        $random_codes = $this->em()->getRepository('AppBundle:Code')
            ->getRandomCodes(5);
        $codes = [];
        foreach($random_codes as $code){ $codes[] = $code->getRandomCode(); }

        $string_of_codes = implode(", ", $codes);

        $data['rand_str'] = $string_of_codes;
        return $this->render('code/code.html.twig', $data);
    }

    /**
     * @Route("/code/check", name="check_code_validity")
     */
    public function checkCodeAction(Request $request)
    {

        $data = [];
        $message = "";
        $user = $this->user();
        $now = new \DateTime("now");
        $code_string = str_replace(' ', '', $request->request->get('code'));
        $codes = explode(',', trim($code_string));
        $timetable_id = $request->request->get('timetable_id');
        $timetable = $this->find('Timetable', $timetable_id);
        $address = $this->generateUrl('my_downloads', ['tbl' => $timetable_id]);
        $number_of_classes = count($timetable->getClasses());
        $number_of_teachers = count($timetable->getTeachers());
        $bonus_downloads = 10;
        $total_downloads = ($number_of_classes + $number_of_teachers) * 2 + $bonus_downloads;
        $count_good_codes = 0;
        foreach ($codes as $code) {
          $code_exists = $this->em()->getRepository('AppBundle:Code')->findByRandomCode($code);
          $virgin_code_exists = $this->em()->getRepository('AppBundle:Code')
            ->findOneBy(
                  array('randomCode' => $code, 'status' => 'virgin'),
                  array('id' => 'ASC')
              );

          if($code_exists){
              //code exists
              if($virgin_code_exists){
                  $message .= "$code works fine<br />";
                  $success = true;
                  $count_good_codes++;
              } else {
                  $message .= "$code has been used<br />";
                  $success = false;
                  break;
              }
          } else {
              //code doesn't exist
              $message .= "$code doesn't exist.<br />";
              $success = false;
              break;
          }
        }
        if($count_good_codes < 3){
          $message .= "The codes are not enough. write all three codes seperated by comma. A code costs 100/= <br />";
        }

        if($success == true and $count_good_codes >= 3){
          foreach ($codes as $code) {
            $code_exists = $this->em()->getRepository('AppBundle:Code')->findOneByRandomCode($code);
            if($code_exists){
                  $code_exists->setStatus("used");
                  $this->save($code_exists);
              }
            }
            // ssu-017,dko-314,cza-846
            $download = new Download();
            $download->setTimes($total_downloads);
            $download->setStatus(1);
            $download->setLink($timetable_id);
            $download->setUser($user);
            $download->setDescription($timetable->getTitle());
            $download->setTimetable($timetable);
            $this->save($download);
        } else {
          // do nothing
        }

        $data['address'] = $address;
        $data['message'] = $message;
        $data['success'] = $success;
        return new JsonResponse($data);
    }

	public function getRandStr(){
	  	$a = $b = '';

	  	for($i = 0; $i < 3; $i++){
		    $a .= chr(mt_rand(65, 90)); // see the ascii table why 65 to 90.
		    $b .= mt_rand(0, 9);
		}

  		return $a .'-'. $b;
	}

    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    private function find($entity, $id){
        $entity = $this->em()->getRepository("AppBundle:$entity")->find($id);
        return $entity;
    }

    private function save($entity){
        $this->em()->persist($entity);
        $this->em()->flush();
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

}
