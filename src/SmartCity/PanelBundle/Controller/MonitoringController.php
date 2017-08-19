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
use Symfony\Component\HttpFoundation\JsonResponse;

class MonitoringController extends Controller
{
    /**
     * @Route("/" , name="panel_monitoring_index", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        return array(
            'data' => $user->getRoles()
        );
    }

    /**
     * @Route("/map-marker-cluster" , name="panel_monitoring_map_marker_cluster", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("POST")
     */
    public function mapMarkerClusterAction(Request $request)
    {

        $bound = $request->get('bound');
        $zoomLevel = $request->get('zoomLevel');

        $elasticService = $this->get('SmartCity.elastic.service');

        $result = $elasticService->mapMarkerClusterQuery($bound, $zoomLevel);

        return new JsonResponse($result['aggregations']['clustering']);
    }



}
