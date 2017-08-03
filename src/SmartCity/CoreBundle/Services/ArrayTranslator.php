<?php
// src/SmartCity/CoreBundle/Services/ArrayTranslator.php
namespace SmartCity\CoreBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

/**
 * Class ArrayTranslator
 * @package SmartCity\Bundle\Services
 */
class ArrayTranslator
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    protected $translator;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->translator = $container->get('translator');
    }

    public function translate($labels)
    {
        $translatedLabels = array();

        foreach ($labels as $key => $value) {
            $translatedLabels[$key] = $this->translator->trans($value, array(), 'labels');
        }

        return $translatedLabels;
    }
}