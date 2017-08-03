<?php
namespace SmartCity\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * SmartCity\CoreBundle\Entity\Configuration
 *
 * @ORM\Table(name="Configurations")
 * @ORM\Entity(repositoryClass="SmartCity\CoreBundle\Entity\Repository\ConfigurationRepository")
 */
class Configuration
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="group_code", type="string", length=255, nullable=false)
     */
    private $groupCode;

    /**
     * @ORM\Column(name="key", type="string", length=255, nullable=false)
     */
    private $key;

    /**
     * @ORM\Column(name="value", type="string", length=1023, nullable=false)
     */
    private $value;

    /**
     * toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getGroupCode() . ':' . $this->getKey();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set groupCode
     *
     * @param string $groupCode
     * @return Configuration
     */
    public function setGroupCode($groupCode)
    {
        $this->groupCode = $groupCode;

        return $this;
    }

    /**
     * Get groupCode
     *
     * @return string 
     */
    public function getGroupCode()
    {
        return $this->groupCode;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return Configuration
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Configuration
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
