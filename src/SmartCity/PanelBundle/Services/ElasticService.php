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
            // ->setHosts([
            //    'host' => $host,
            //    'port' => $port
            // ])
            ->setHosts([
                'localhost:9200'
            ])
            ->build()
        ;

        ElasticService::$index = $index;
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

    ## get the latest state object value'
    ## for log
    public function sensorLatestLogQuery($sensorId) {
        $searchParams = [
            'index' => ElasticService::$index,
            'type' => ElasticService::$log,
            'size' => 1,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => [
                                    'device_id' => [
                                        'value' => $sensorId
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'sort' => [
                    [
                        'time' => [
                            'order' => 'desc'
                        ]
                    ]
                ]
            ]
        ];

        $response = ElasticService::$client->search($searchParams);
        return $response;
    }

    ## get metric
    ## for log
    public function sensorMetricValueQuery($sensorId, $fun, $field, $startTime, $endTime) {

        $searchParams = [
            'index' => ElasticService::$index,
            'type' => ElasticService::$log,
            'size' => 1,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => [
                                    'device_id' => [
                                        'value' => $sensorId
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'range' => [
                        'date_range' => [
                            'field' => 'time',
                            'ranges' => [
                                'from' => $startTime,
                                'to' => $endTime
                            ]
                        ],
                        'aggs' => [
                            $fun => [
                                $fun => [
                                    'field' => "state.$field"
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

    ##
    ## for log
    public function sensorAggregationLogQuery($sensorId, $fun, $field, $startTime, $endTime, $ranges)
    {

        /* "aggs" => [
         "histogram" => [
                 "date_histogram" => [
                     "field" => "time",
                     "interval" => $interval_key,
                      "offset" => $interval_offset,
                     "keyed" => true
                 ],
                 "aggs" => [
                     $fun => [
                         $fun => [
                             "field" => "state.$field"
                         ]
                     ]
                 ]
             ]
        ] */
        $searchParams = [
            'index' => ElasticService::$index,
            'type' => ElasticService::$log,
            // 'size' => 1,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => [
                                    'device_id' => [
                                        'value' => $sensorId
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                "aggs" => [
                    "ranges" => [
                        "date_range" => [
                            "field" => "time",
                            "ranges" => $ranges,
                            // "keyed" => true
                        ],
                        "aggs" => [
                            'value' => [
                                $fun => [
                                    "field" => "state.$field"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
//        print "<pre>";print_r($searchParams);die();
        $response = ElasticService::$client->search($searchParams);
        return $response;
    }

}