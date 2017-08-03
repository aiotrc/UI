<?php
namespace SmartCity\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;

/**
 * SmartCity\UserBundle\Entity\UserVerificationToken
 *
 * @ORM\Table(name="UserVerificationTokens",uniqueConstraints={
 *          @ORM\UniqueConstraint(name="cellphoneUser",columns={"cellphone","user_id"})
 *     })
 * @ORM\Entity(repositoryClass="SmartCity\UserBundle\Entity\Repository\UserVerificationTokenRepository")
 */
class UserVerificationToken
{
    use TimestampableEntity;
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SmartCity\UserBundle\Entity\User", inversedBy="verificationToken")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(name="sms_token", type="string", length=32, nullable=false)
     */
    private $smsToken;

    /**
     * @ORM\Column(name="email_token", type="string", length=32, nullable=true)
     */
    private $emailToken;

    /**
     * @ORM\Column(name="used", type="boolean", nullable=false, nullable=true)
     */
    private $used = false;

    /**
     * @ORM\Column(name="expired", type="boolean", nullable=true)
     */
    private $expired = false;

    /**
     * @ORM\Column(name="used_at", type="datetime", nullable=true)
     */
    private $usedAt = NULL;

    /**
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     */
    private $expiresAt = NULL;

    /**
     * @ORM\Column(name="cellphone", type="phone_number" , nullable=true)
     */
    private $cellphone;

    /**
     * @ORM\Column(name="plain_cellphone", type="string", length=32, nullable=true)
     */
    private $plainCellphone;

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
     * Set used
     *
     * @param boolean $used
     * @return UserVerificationToken
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
     * @return UserVerificationToken
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
     * @return mixed
     */
    public function getSmsToken()
    {
        return $this->smsToken;
    }

    /**
     * @param mixed $smsToken
     * @return $this
     */
    public function setSmsToken($smsToken)
    {
        $this->smsToken = $smsToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailToken()
    {
        return $this->emailToken;
    }

    /**
     * @param mixed $emailToken
     * @return $this
     */
    public function setEmailToken($emailToken)
    {
        $this->emailToken = $emailToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsedAt()
    {
        return $this->usedAt;
    }

    /**
     * @param mixed $usedAt
     * @return $this
     */
    public function setUsedAt($usedAt)
    {
        $this->usedAt = $usedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param mixed $expired
     * @return $this
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param mixed $expiresAt
     * @return $this
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Set cellphone
     *
     * @param PhoneNumber $cellphone
     * @return UserVerificationToken
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get cellphone
     *
     * @return PhoneNumber
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * Set plainCellphone
     *
     * @param string $plainCellphone
     * @return UserVerificationToken
     */
    public function setPlainCellphone($plainCellphone)
    {
        $this->plainCellphone = $plainCellphone;

        return $this;
    }

    /**
     * Get plainCellphone
     *
     * @return string 
     */
    public function getPlainCellphone()
    {
        return $this->plainCellphone;
    }
}
