<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;


class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="view_profile")
     */
    public function profileAction(Request $request)
    {

        $data = [];
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data['user'] = $user;

        return $this->render('user/profile.html.twig', $data );

    }

}