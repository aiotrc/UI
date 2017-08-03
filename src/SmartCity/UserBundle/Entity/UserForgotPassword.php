<?php
namespace SmartCity\UserBundle\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use SmartCity\UserBundle\Entity\Constants\UserConstants;
use Doctrine\ORM\Mapping as ORM;

/**
 * SmartCity\UserBundle\Entity\UserForgotPassword
 *
 * @ORM\Table(name="UsersForgotPasswords")
 * @ORM\Entity(repositoryClass="SmartCity\UserBundle\Entity\Repository\UserForgotPasswordRepository")
 */
class UserForgotPassword
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SmartCity\UserBundle\Entity\User", inversedBy="forgotTokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(name="type", type="string", columnDefinition="enum('SMS', 'EMAIL')")
     */
    private $type = UserConstants::FORGET_PASSWORD_TYPE_SMS;

    /**
     * @ORM\Column(name="token", type="string", length=32, nullable=false)
     */
    private $token;

    /**
     * @ORM\Column(name="used", type="boolean", nullable=false)
     */
    private $used = false;

    /**
     * @ORM\Column(name="tries", type="integer", nullable=false)
     */
    private $generateTries = 1;

    /**
     * @ORM\Column(name="expired", type="boolean", nullable=false)
     */
    private $expired = false;

    /**
     * @ORM\Column(name="expires_at", type="datetime")
     */
    private $expiresAt = NULL;

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
     * Set token
     *
     * @param string $token
     * @return UserForgotPassword
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set used
     *
     * @param boolean $used
     * @return UserForgotPassword
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set user
     *
     * @param \SmartCity\UserBundle\Entity\User $user
     * @return UserForgotPassword
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SmartCity\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set generateTries
     *
     * @param integer $generateTries
     * @return UserForgotPassword
     */
    public function setGenerateTries($generateTries)
    {
        $this->generateTries = $generateTries;

        return $this;
    }

    /**
     * Get generateTries
     *
     * @return integer
     */
    public function getGenerateTries()
    {
        return $this->generateTries;
    }

    /**
     * Increase generateTries
     *
     * @return UserForgotPassword
     */
    public function increaseGenerateTries()
    {
        $this->generateTries ++;

        return $this;
    }

    /**
     * Set expired
     *
     * @param boolean $expired
     * @return UserForgotPassword
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * Get expired
     *
     * @return boolean 
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     * @return UserForgotPassword
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return UserForgotPassword
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
