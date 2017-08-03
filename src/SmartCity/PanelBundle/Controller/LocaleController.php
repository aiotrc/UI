<?php

namespace SmartCity\PanelBundle\Controller;

use SmartCity\UserBundle\Annotation\FrontendAccessible;
use SmartCity\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class LocaleController extends Controller
{
    /**
     * Change language of the software
     * @Route("/switch" , name="panel_locale_change")
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeLocaleAction(Request $request)
    {
        $locale = $request->getLocale();
        $request->getSession()->set('_locale', $locale);

        $referer = $request->headers->get('referer');
        if (empty($referer)) {
            throw $this->createNotFoundException();
        }

        return $this->redirect($referer);
    }
}
