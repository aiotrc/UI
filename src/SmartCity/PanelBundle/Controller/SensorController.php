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

class SensorController extends Controller
{
    /**
     * @Route("/map" , name="panel_sensor_map", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     */
    public function mapAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        return array(
            'data' => $user->getRoles()
        );
    }

    /**
     * @Route("/map/cluster" , name="panel_sensor_map_cluster", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("POST")
     */
    public function mapClusterAction(Request $request)
    {

        $bound = $request->get('bound');
        $zoomLevel = $request->get('zoomLevel');

        $elasticService = $this->get('SmartCity.elastic.service');
        $result = $elasticService->mapMarkerClusterQuery($bound, $zoomLevel);

        return new JsonResponse($result['aggregations']['clustering']);
    }

    /**
     * @Route("/spec" , name="panel_sensor_spec", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("POST")
     */
    public function specAction(Request $request)
    {

        $senosrId = $request->get('sensorId');
        $elasticService = $this->get('SmartCity.elastic.service');
        $result = $elasticService->sensorSpecQuery($senosrId);

        return new JsonResponse($result['hits']['hits'][0]['_source']);
    }

    /**
     * @Route("/log" , name="panel_sensor_log", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("POST")
     */
     public function logAction(Request $request)
     {
 
         $senosrId = $request->get('sensorId');
        //  $elasticService = $this->get('SmartCity.elastic.service');
        //  $result = $elasticService->sensorSpecQuery($senosrId);
 
         return new JsonResponse([]);
     }



}
