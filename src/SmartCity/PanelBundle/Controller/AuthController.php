<?php

namespace SmartCity\PanelBundle\Controller;

use SmartCity\UserBundle\Annotation\FrontendAccessible;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use SmartCity\CoreBundle\Controller\BaseController;
use SmartCity\UserBundle\Entity\User;
use SmartCity\UserBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Auth controller.
 *
 * @Route("/")
 */
class AuthController extends BaseController
{
    /**
     * @Route(path="/login", name="panel_auth_login")
     * @FrontendAccessible(adminAccessible=true)
     * @Template
     */
    public function loginAction(Request $request)
    {
        // dump(123);
        // die();
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error' => $error,
        );
    }

    /**
     * @Route(path="/login_check", name="panel_auth_login_check")
     * @FrontendAccessible(guestAccessible=true)
     */
    public function loginCheckAction() {
        return new Response('');
    }

    /**
     * @Route(path="/logout", name="panel_auth_logout")
     * @FrontendAccessible(customerAccessible=true)
     */
    public function logoutAction() {
        return new Response('');
    }
}
