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

class CityController extends Controller
{
    /**
     * @Route("/" , name="panel_city_index", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }


    /**
     * Query city to find a title
     *
     * AJAX Request
     * JSON Response
     *
     * @Route("/suggest", name="panel_city_suggest", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     */
    public function suggestAction(Request $request)
    {
        
        $province_id = $request->get('province_id');
        $query = $request->get('query');

        $em = $this->getDoctrine()->getEntityManager();

        $cityModel = $em->getRepository('SmartCityGeoBundle:City');
        $cities = $cityModel->getCityLikeTitle($province_id, $query);

        return new JsonResponse($cities);
    }

}
