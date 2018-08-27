<?php 

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $from = "mylepostsolutions@gmail.com";
            $to = $user->getEmail();
            $subject = "Please Verify your Timetabler Email Address";

            $this->verificationEmailAction($from, $to, $subject, $user->getId());

            $this->addFlash('success', 'Account created successfully. Check your email '.$user->getEmail().' for verification link!' );
            return $this->redirectToRoute('registration_verify_wait');

        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function verificationEmailAction($from, $to, $subject, $id)
    {

        $mailer = $this->get('mailer');

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($this->renderView('mail/verify_msg.html.twig', ['id' => $id]), 'text/html')
        ;
        $mailer->send($message);
        return new Response('<html><body>The email has been sent successfully!</body></html>');
    }

    public function successEmailAction($from, $to, $subject, $user_f_name)
    {

        $mailer = $this->get('mailer');

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($this->renderView('mail/notification.html.twig', ['user_f_name'=>$user_f_name]), 'text/html')
        ;
        $mailer->send($message);
        return new Response('<html><body>The email has been sent successfully!</body></html>');
    }

    /**
     * @Route("/register/verify/{id}", name="registration_verify")
     */
    public function verifyAction(Request $request, $id)
    {
        $user = $this->em()->getRepository('AppBundle:User')
            ->find($id);
        $data = [];
        $data['id'] = $id;
        if($user){
            $data['user'] = $user;
            $user->setActive(1);
            $this->save($user);
            $from = "mylepostsolutions@gmail.com";
            $to = $user->getEmail();
            $subject = "Welcome to ExamPro App";
            $this->successEmailAction($from, $to, $subject, $user->getFName() );
            $data['verification_title'] = "Account Activated!";
            $data['verification_message'] = "Your Account is successfully verified and activated.";
        } else {
            $data['verification_title'] = "Oops!";
            $data['verification_message'] = "That account doesn't exist!";
        }
        return $this->render('mail/verify_page.html.twig', $data );
    }

    /**
     * @Route("/register/verification/wait", name="registration_verify_wait")
     */
    public function verifyWaitAction(Request $request)
    {
        return $this->render('mail/verify_wait.html.twig');
    }

    private function save($entity){
        $this->em()->persist($entity);
        $this->em()->flush();
        return true;
    }

    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    
}