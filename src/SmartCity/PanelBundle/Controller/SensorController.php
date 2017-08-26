<?php

namespace SmartCity\PanelBundle\Controller;

use Elastica\JSON;
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
     * @Method("GET")
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
     * @return JsonResponse
     * @Route("/log/latestLog")
     * @Method("GET")
     * example: http://smartcity.local:8081/sensor/log/latestLog?sensorId=1_1&termName=humidity
     */
    public function latestValueLogAction(Request $request)
    {
//        TODO: http status code response
        $sensorId = $request->get('sensorId');
//        think term is humidity or ...
        $termName = $request->get('termName');
        if ($sensorId == null || $termName == null) {
            return new JsonResponse([
                'error' => 'not valid url',
            ]);
        }
        $elasticService = $this->get('SmartCity.elastic.service');
        $elasticResponse = $elasticService->latestLog($sensorId)['hits'];
        if ($elasticResponse['total'] > 0) {

            if (!array_key_exists($termName, $elasticResponse['hits'][0]['_source']['state'])) {
                return new JsonResponse([
                    'error' => 'not valid url',
                ]);
            }
            return new JsonResponse([
                'device_id' => $elasticResponse['hits'][0]['_source']['device_id'],
                'time' => $elasticResponse['hits'][0]['_source']['time'],
                'term' => $elasticResponse['hits'][0]['_source']['state'][$termName]
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
        $sensorId = $request->get('sensorId');
//        think term is humidity or ...
        $termName = $request->get('termName');
        $func = $request->get('func');
        $startTime = $request->get('startTime');
        $eneTime = $request->get('endTime');

//        TODO: must be check that field exist in state data
        if ($sensorId == null || $func == null || $termName == null || $startTime == null || $eneTime == null) {
            return new JsonResponse([
                'error' => 'not valid url',
            ]);
        }
        $elasticService = $this->get('SmartCity.elastic.service');
        $elasticResponse = $elasticService->metricValue($sensorId, $func, $termName, $startTime, $eneTime)['aggregations']['range']['buckets'][0];

        if ($elasticResponse['doc_count'] > 0) {

            return new JsonResponse([
                'value' => $elasticResponse[$func]['value']
            ]);
        }

        return new JsonResponse(['error'=> 'not found']);
    }


    /**
     * @return JsonResponse
     * @Route("/log/aggregation")
     * @Method("POST")
     * example: http://smartcity.local:8081/sensor/log/aggragation?interval=month&termName=humidity&func=min&startTime=2011-12-14T01:22:27Z&endTime=2012-04-27T00:58:36Z
     * {
        "termName": "humidity",
        "func": "min",
        "startTime": "2007-04-02T00:00:00.000Z",
        "endTime": "2019-04-02T00:00:00.000Z",
        "interval": {
                "y1": {
                "to": "2019-04-02T00:00:00.000Z",
                "from": "2018-04-02T00:00:00.000Z"
                },
                "y2": {
                "from": "2017-04-02T00:00:00.000Z",
                "to": "2018-04-02T00:00:00.000Z"
                },
                "y3": {
                "from": "2007-04-02T00:00:00.000Z",
                "to": "2016-04-02T00:00:00.000Z"
                }
            }
        }
     * available func param: min, max, median, sum, avg
     */
    public function aggregationLogAction(Request $request)
    {
        $interval = $request->get('interval');
//        think term is humidity or ...
        $termName = $request->get('termName');
        $func = $request->get('func');
        $startTime = $request->get('startTime');
        $eneTime = $request->get('endTime');

//        TODO: must be check that field exist in state data
        if ($interval == null || $func == null || $termName == null || $startTime == null || $eneTime == null) {
            return new JsonResponse([
                'error' => 'not valid url',
            ]);
        }

        $elasticService = $this->get('SmartCity.elastic.service');
        $elasticResponse = $elasticService->aggregationField($interval, $func, $termName, $startTime, $eneTime)['aggregations']['interval']['buckets'];

        return new JsonResponse($elasticResponse);
    }

}
