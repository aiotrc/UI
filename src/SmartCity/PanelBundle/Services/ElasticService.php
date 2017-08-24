<?php

namespace SmartCity\PanelBundle\Services;
use Elasticsearch\ClientBuilder;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class ElasticService
 */
class ElasticService
{

    protected static $client = null;
    protected static $index = 'sensor';

    protected static $log = 'log';
    protected static $spec = 'spec';
    protected static $conf = 'conf';

    public function __construct($host, $port, $index)
    {
        $logger = ClientBuilder::defaultLogger('/path/to/logs/', Logger::INFO);
        $serializer = '\Elasticsearch\Serializers\SmartSerializer';

        ElasticService::$client = ClientBuilder::create()
            // ->setSerializer($serializer)
            // ->setLogger($logger)
            ->setHosts([
                'host' => $host,
                'port' => $port
            ])
            ->build()
        ;

        ElasticService::$index = $index;
    }

    ## log query
    public function rangeItemQuery($data)
    {
        $searchParams = [

            'index' => ElasticService::$index,
            'type' => ElasticService::$log,
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

        $response = ElasticService::$client->search($searchParams);
        return $response;
    }

    ## map marker clustering query
    public function mapMarkerClusterQuery($mapBounds, $zoomLevel)
    {
        $searchParams = [
            'index' => ElasticService::$index,
            'type' => ElasticService::$spec,
            "size" => 0,
            'body' => [
                'aggs' => [
                    'clustering' => [ // name of aggregation
                        'filter' => [
                            'geo_bounding_box' => [ 
                                'location' => [ // geo_point field
                                    'top_left' => [
                                        'lat' => $mapBounds['top_left_lat'],
                                        'lon' => $mapBounds['top_left_lon']
                                    ],
                                    'bottom_right' => [
                                        'lat' => $mapBounds['bottom_right_lat'],
                                        'lon' => $mapBounds['bottom_right_lon']
                                    ]
                                ]
                            ]
                        ],
                        'aggs' =>[
                            'markers' => [ // name of aggregation
                                'geohash_grid' => [
                                    'field' => 'location', // filed on which the aggregation need to work
                                    'precision' => $zoomLevel //zoom can have values from 1 to 8
                                ],
                                "aggs" => [
                                    "device" => [ // name of aggregation
                                        "terms" => [
                                            "script" => "doc['device_id'].value + '|' + doc['type'].value + '|' + doc['brand'].value",
                                            "size" => 1,
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        $response = ElasticService::$client->search($searchParams);
        return $response;
    }

    ## map marker clustering query
    public function sensorSpecQuery($sensorId)
    {
        $searchParams = [
            'index' => ElasticService::$index,
            'type' => ElasticService::$spec,
            'size' => 1,
            'terminate_after' => 1,
            'body' => [
                "query" => [
                    "term" => [ "device_id" => $sensorId ]
                ]
            ]
        ];
        
        $response = ElasticService::$client->search($searchParams);
        return $response;
    }
}