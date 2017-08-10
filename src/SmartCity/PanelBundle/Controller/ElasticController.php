<?php

namespace SmartCity\PanelBundle\Controller;

use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ElasticController extends Controller
{
    /**
     * Lists all Role entities.
     *
     * @Route("/", name="elastic_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $finder = $this->container->get("fos_elastica.index.bank.account");
//        var_dump($finder->findHybrid("Virginia"));
        $boolQuery = new BoolQuery();
        $fieldQuery = new Match();
        $fieldQuery->setFieldQuery('address', 'Avenue');
        $fieldQuery->setFieldParam('address', 'analyzer', 'my_analyzer');
        $boolQuery->addShould($fieldQuery);
        var_dump($finder->find($boolQuery));
        die();
        return JsonResponse::create();
    }
}
