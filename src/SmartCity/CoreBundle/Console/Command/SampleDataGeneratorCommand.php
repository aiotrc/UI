<?php

namespace SmartCity\CoreBundle\Console\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SampleDataGeneratorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('SmartCity:sampledata:generate')
            ->setDescription('Generate Sample Data for Sensors')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $elastic_config = [
            'index' => 'sensor'
        ];

        $types = [
            'log' => [
                'number_of_sample' => 1000,
                'variables' => [
                    'device_id' => [
                        'type' => 1,
                        'combination' => true,
                        'data' => [
                            range(1,10, 1),
                            range(1, 500, 1)
                        ]
                    ],
                    'time' => [
                        'type' => 1,
                        'combination' => false,
                        'data' => range(strtotime('2016-12-20 06:31:21'), strtotime('2016-12-29 09:31:21'), 1)
                    ],
                    'state' => [
                        'type' => 2,
                        'variables' => [
                            'humidity' => range(10, 20, 1),
                            'temperature' => range(10, 20, 1)
                        ]
                    ]
                ]
            ],
            'spec' => [
                'number_of_sample' => 1,
                'variables' => [
                    'device_id' => [
                        'type' => 1,
                        'combination' => true,
                        'data' => [
                            range(1,10, 1),
                            range(1, 500, 1)
                        ]
                    ],
                    'type' => [
                        'type' => 1,
                        'combination' => false,
                        'data' => ['lamp', 'thermometer', 'gasmeter', 'watermeter']
                    ],
                    'brand' => [
                        'type' => 1,
                        'combination' => false,
                        'data' => ['huawei', 'zigbee']
                    ],
                    'location' => [
                        'type' => 2,
                        'variables' => [
                            'lon' => range(44.5166, 59.7656, 0.0001),
                            'lat' => range(27.4888, 37.4400, 0.0001)

                        ]
                    ],
                    'attributes' => [
                        'type' => 2,
                        'variables' => [
                            'weight' => range(300, 2000, 0.1),
                            'height' => range(10, 20, 1),
                            'width' => range(10, 20, 1)
                        ]
                    ]
                ]
            ],
            'conf' => [
                'number_of_sample' => 1000,
                'variables' => [
                    'device_id' => [
                        'type' => 1,
                        'combination' => true,
                        'data' => [
                            range(1,10, 1),
                            range(1, 500, 1)
                        ]
                    ],
                    'time' => [
                        'type' => 1,
                        'combination' => false,
                        'data' => range(strtotime('2009-12-29 06:31:21'), strtotime('2009-12-29 09:31:21'), 1)
                    ],
                    'settings' => [
                        'type' => 2,
                        'variables' => [
                            'on' => [true, false]
                        ]
                    ]
                ]
            ]
        ];

        foreach ($types as $type => $data) {
            $output->writeln("Generating ".$data['number_of_sample']." <fg=yellow>$type</> Data ...");
            $file = fopen("sampleData/".$type."s.json", 'w+') or die('unable to open');
            
            for ($i=0; $i < $data['number_of_sample']; $i++) {
                $index = [
                    'index' => [
                        '_index' => $elastic_config['index'],
                        '_type' => $type
                    ]
                ];
                fwrite($file, json_encode($index)."\n");
                $result = [];
                foreach ($data['variables'] as $variable=>$values) {
                    
                    if ($values['type'] == 1) {
                        if ($values['combination']) {
                            // $result[$variable] = $values['data'][0][array_rand($values['data'][0])].'_'.$values['data'][1][array_rand($values['data'][1])];
                            $result[$variable] = $i;
                        }
                        else {
                            if ($variable=='time') {
                                $result[$variable] = date('Y-m-d', $values['data'][array_rand($values['data'])]).'T'.
                                    date('H:i:s', $values['data'][array_rand($values['data'])]).'Z';
                            }
                            else {
                                $result[$variable] = $values['data'][array_rand($values['data'])];
                            }
                        }
                    }

                    if ($values['type'] == 2) {
                        foreach ($values['variables'] as $variable_2=>$value) {
                            $result[$variable][$variable_2] = $value[array_rand($value)];
                        }
                    }

                }
                fwrite($file, json_encode($result)."\n");
            }

            fclose($file);
            $output->writeln("<info>[DONE]</info> \n");
        }

        //$int= mt_rand(1262055681, strtotime('2009-12-29 09:31:21'));
        //$string = date("Y-m-d H:i:s",$int);
        //echo $string;

        function rand_date($min_date, $max_date) {
            /* Gets 2 dates as string, earlier and later date.
            Returns date in between them.
            */

            $min_epoch = strtotime($min_date);
            $max_epoch = strtotime($max_date);

            $rand_epoch = rand($min_epoch, $max_epoch);

            return date('Y-m-d H:i:s', $rand_epoch);
        }
        

        $output->writeln("<info>All Sample Data Successfuly Generated</info>\n");
    }
}
