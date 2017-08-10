<?php

namespace SmartCity\PanelBundle\Controller;

use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elasticsearch\Client;
use SmartCity\PanelBundle\Elasticsearch\Elasticsearch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ElasticController extends Controller
{
    protected static $ElasticClient = null;
    /**
     * Lists all Role entities.
     *
     * @Route("/", name="elastic_index")
     * @Method("GET")
     */
    public function indexAction()
    {
//        $this->container
        $response = Elasticsearch::rangeItemQuery([
            'time' => [
                'gte' => '2016-10-24T23:05:34Z'
            ]
        ]);
        return JsonResponse::create($response['hits']);
    }



}
