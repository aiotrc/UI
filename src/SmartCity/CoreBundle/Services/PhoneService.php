<?php
// src/SmartCity/CoreBundle/Services/PhoneService.php
namespace SmartCity\CoreBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use SmartCity\CoreBundle\Entity\Constants\ConfigurationConstants;

/**
 * Class PhoneService
 * @package SmartCity\Bundle\Services
 */
class PhoneService
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;


    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function normalize($phone)
    {
        $phone = str_replace(' ', '', $phone); // remove spaces
        $matches = preg_split(ConfigurationConstants::PHONE_PATTERN, $phone); //match with pattern
        $phone = $matches[0] != '' ? $matches[0] : $matches[1];
        
        return $phone = ConfigurationConstants::PHONE_COUNTRY_CODE.$phone;
    }
}