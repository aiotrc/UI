<?php

namespace SmartCity\PanelBundle\Controller;

use Elastica\JSON;
use SmartCity\UserBundle\Annotation\FrontendAccessible;
use SmartCity\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SmartCity\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class SensorController extends BaseController
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
     * @return JsonResponse
     * @Route("/map/cluster" , name="panel_sensor_map_cluster", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("POST")
     */
    public function mapClusterAction(Request $request)
    {

        $bound = $this->required('bound');
        $zoomLevel = $this->required('zoomLevel');

        $elasticService = $this->get('SmartCity.elastic.service');
        $elasticResult = $elasticService->mapMarkerClusterQuery($bound, $zoomLevel);

        return new JsonResponse($elasticResult['aggregations']['clustering']);
    }

    /**
     * @return JsonResponse
     * @Route("/spec" , name="panel_sensor_spec", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("POST")
     */
    public function specAction(Request $request)
    {

        $senosrId = $this->required('sensorId');

        $elasticService = $this->get('SmartCity.elastic.service');
        $elasticResult = $elasticService->sensorSpecQuery($senosrId);

        if ($elasticResult['hits']['total'] > 0) {
            return new JsonResponse([
                'sensor_spec' => $elasticResult['hits']['hits'][0]['_source']
            ]);
        }

        return new JsonResponse(['error'=> 'not found']);
    }

    /**
     * @return JsonResponse
     * @Route("/log/latest" , name="panel_sensor_latest_log", options={"expose"=true})
     * @Method("GET")
     * example: http://smartcity.local:8081/sensor/log/latestLog?sensorId=1_1
     */
    public function latestLogAction(Request $request)
    {
        $sensorId = $this->required('sensorId');

        $elasticService = $this->get('SmartCity.elastic.service');
        $elasticResult = $elasticService->latestLog($sensorId);

        if ($elasticResult['hits']['total'] > 0) {
            return new JsonResponse([
                'sensor_latest_log' => $elasticResult['hits']['hits'][0]['_source']
            ]);
        }

        return new JsonResponse(['error'=> 'not found']);
    }

    /**
     * @return JsonResponse
     * @Route("/log/field")
     * @Method("GET")
     * example: http://smartcity.local:8081/sensor/log/field?sensorId=1_1&termName=humidity&func=min&startTime=2011-12-14T01:22:27Z&endTime=2012-04-27T00:58:36Z
     * available func param: min, max, median, sum, avg
     */
    public function funcFieldLogAction(Request $request)
    {
        $sensorId = $this->required('sensorId');
        // think term is humidity or ...
        $termName = $this->required('termName');
        $func = $this->required('func');
        $startTime = $this->required('startTime');
        $eneTime = $this->required('endTime');

        $elasticService = $this->get('SmartCity.elastic.service');
        $elasticResult = $elasticService->metricValue($sensorId, $func, $termName, $startTime, $eneTime)['aggregations']['range']['buckets'][0];

        if ($elasticResult['doc_count'] > 0) {

            return new JsonResponse([
                'value' => $elasticResult[$func]['value']
            ]);
        }

        return new JsonResponse(['error'=> 'not found']);
    }


    /**
     * @return JsonResponse
     * @Route("/log/aggregation")
     * @Method("GET")
     * example: http://smartcity.local:8081/sensor/log/aggragation?interval=month&termName=humidity&func=min&startTime=2011-12-14T01:22:27Z&endTime=2012-04-27T00:58:36Z
     * available func param: min, max, median, sum, avg
     */
    public function aggregationLogAction(Request $request)
    {
        $interval = $this->required('interval');
        // think term is humidity or ...
        $termName = $this->required('termName');
        $func = $this->required('func');
        $startTime = $this->required('startTime');
        $eneTime = $this->required('endTime');

        // TODO: must be check that field exist in state data
        $elasticService = $this->get('SmartCity.elastic.service');
        $elasticResponse = $elasticService->aggregationField($interval, $func, $termName, $startTime, $eneTime)['aggregations']['interval']['buckets'];

        return new JsonResponse($elasticResponse);
    }

}
