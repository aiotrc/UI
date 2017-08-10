<?php
/**
 * Created by PhpStorm.
 * User: zrhm7232
 * Date: 8/10/17
 * Time: 3:33 PM
 */

namespace SmartCity\PanelBundle\Elasticsearch;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elasticsearch\ClientBuilder;

class Elasticsearch extends Controller
{
    public static $client = null;
    public static $clientBuilder = null;
    public static $host = 'localhost:9200';
    public static $index = 'test1';
    public static $log = 'log';

    public static function create()
    {
        if (Elasticsearch::$client != null) {
            return Elasticsearch::$client;
        }
        Elasticsearch::$clientBuilder = ClientBuilder::create();   // Instantiate a new ClientBuilder
        // TODO must put in env
        Elasticsearch::$clientBuilder->setHosts([Elasticsearch::$host]);           // Set the hosts
        Elasticsearch::$client = Elasticsearch::$clientBuilder->build();

        return Elasticsearch::$client;
    }

    ## log query
    public static function rangeItemQuery($data)
    {

        $client = Elasticsearch::create();
        $searchParams = [
            // TODO must put in env
            'index' => Elasticsearch::$index,
            'type' => Elasticsearch::$log,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'range' => $data
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $response = $client->search($searchParams);
        return $response;
    }
}