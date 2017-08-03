<?php

namespace SmartCity\PanelBundle\Controller;

use SmartCity\UserBundle\Annotation\FrontendAccessible;
use SmartCity\GeoBundle\Entity\Province;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProvinceController extends Controller
{
    /**
     * @Route("/" , name="panel_province_index", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }


    /**
     * Query province to find a title
     *
     * AJAX Request
     * JSON Response
     *
     * @Route("/find", name="panel_province_find", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     */
    public function findAction(Request $request)
    {   
        $query = $request->get('query');
        $em = $this->getDoctrine()->getEntityManager();

        $provinceModel = $em->getRepository('SmartCityGeoBundle:Province');
        $provinces = $provinceModel->getProvinceLikeTitle($query);

        return new JsonResponse($provinces);
    }

}
