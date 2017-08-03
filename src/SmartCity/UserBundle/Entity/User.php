<?php
namespace SmartCity\UserBundle\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use SmartCity\UserBundle\Entity\Constants\UserConstants;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * SmartCity\UserBundle\Entity\User
 *
 * @ORM\Table(name="Users"),
 * @ORM\Entity(repositoryClass="SmartCity\UserBundle\Entity\Repository\UserRepository")
 * @ORM\EntityListeners({"SmartCity\UserBundle\Entity\Lifecycle\UserListener"})
 * @Vich\Uploadable
 */
class User implements AdvancedUserInterface, EquatableInterface, \Serializable, \JsonSerializable, EncoderAwareInterface
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Unmapped
     */
    private $code;

    /**
     * @ORM\Column(name="username", type="string", length=128, unique=true, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(name="email", type="string", length=128, unique=true, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=128, nullable=false)
     */
    private $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="salt", type="string", length=32, nullable=true)
     */
    private $salt;

    /**
     * @ORM\Column(name="firstname", type="string", length=64)
     */
    private $firstname = "";

    /**
     * @ORM\Column(name="lastname", type="string", length=64)
     */
    private $lastname = "";

    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="imageName")
     * @var File $imageFile
     */
    protected $imageFile;

    /**
     * @ORM\Column(name="sex", type="string", columnDefinition="enum('MALE', 'FEMALE')", nullable=false)
     */
    private $sex;

    /**
     * @ORM\Column(name="national_code", type="string", length=64, unique=true, nullable=false)
     */
    private $nationalCode;

    /**
     * unmapped
     */
    private $jalaliBirthday;

    /**
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     */
    private $birthday = NULL;

    /**
     * @ORM\Column(name="locale", type="string")
     */
    private $locale = 'fa';

    /**
     * @ORM\Column(name="type", type="string", columnDefinition="enum('FRONTEND', 'BACKEND')")
     */
    private $type = UserConstants::TYPE_BACKEND;

    /**
     * @ORM\Column(name="status", type="string", columnDefinition="enum('ACTIVE', 'DEACTIVE', 'LOCKED')")
     */
    private $status = UserConstants::STATUS_DEACTIVE;

    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment = "";

    /**
     * @ORM\Column(name="cellphone", type="phone_number", unique=true, nullable=true)
     */
    private $cellphone;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    private $plainCellphone;

    /**
     * @ORM\ManyToOne(targetEntity="SmartCity\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     **/
    private $created_by = NULL;

    /**
     * @ORM\ManyToOne(targetEntity="SmartCity\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     **/
    private $updated_by = NULL;

    /**
     * @ORM\Column(name="last_seen", type="datetime", nullable=true)
     */
    private $lastSeen = NULL;

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\OneToMany(targetEntity="UserForgotPassword", mappedBy="user")
     **/
    private $forgotTokens;

    /**
     * @ORM\ManyToMany(targetEntity="SmartCity\UserBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="Users_Roles")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="SmartCity\GeoBundle\Entity\Province", inversedBy="users")
     */
    private $provinces;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->forgotTokens = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->provinces = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Set plain password
     *
     * @param string $password
     * @return User
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * Get plain password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }


    /**
     * Set type
     *
     * @param string $type
     *
     * @return User
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

    /**
     * Set status
     *
     * @param string $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Get user full name
     */
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * Set cellphone
     *
     * @param \libphonenumber\PhoneNumber $cellphone
     *
     * @return User
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get cellphone
     *
     * @return \libphonenumber\PhoneNumber
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }


    /**
     * @return string
     */
    public function getPlainCellphone()
    {
        return $this->plainCellphone;
    }

    /**
     * @param string $plainCellphone
     */
    public function setPlainCellphone($plainCellphone)
    {
        $this->plainCellphone = $plainCellphone;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return User
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string 
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // It is required that at least one field changes if you are using doctrine
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return User
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set createdBy
     *
     * @param \SmartCity\UserBundle\Entity\User $createdBy
     *
     * @return User
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->created_by = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \SmartCity\UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set updatedBy
     *
     * @param \SmartCity\UserBundle\Entity\User $updatedBy
     *
     * @return User
     */
    public function setUpdatedBy(User $updatedBy = null)
    {
        $this->updated_by = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \SmartCity\UserBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * Add forgotToken
     *
     * @param UserForgotPassword $forgotToken
     *
     * @return User
     */
    public function addForgotToken(UserForgotPassword $forgotToken)
    {
        $this->forgotTokens[] = $forgotToken;

        return $this;
    }

    /**
     * Remove forgotToken
     *
     * @param UserForgotPassword $forgotToken
     */
    public function removeForgotToken(UserForgotPassword $forgotToken)
    {
        $this->forgotTokens->removeElement($forgotToken);
    }

    /**
     * Get forgotTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForgotTokens()
    {
        return $this->forgxotTokens;
    }

    /**
     * Add role
     *
     * @param \SmartCity\UserBundle\Entity\Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \SmartCity\UserBundle\Entity\Role $role
     */
    public function removeRole(Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        $roles = $this->roles->toArray();
        return $roles;
       // return $this->roles;
    }

    /**
     * Check if the user has the role
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        foreach ($this->getRoles() as $role) {
            if ($role->getRole() == $roleName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the User's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the User's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the User is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the User is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return ($this->getStatus() !== UserConstants::STATUS_LOCKED);
    }

    /**
     * Checks whether the User's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the User's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the User is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the User is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return (
            $this->getStatus() === UserConstants::STATUS_ACTIVE
        );
    }


    /**
     * Removes sensitive data from the User.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = NULL;
    }


    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * Also implementation should consider that $User instance may implement
     * the extended User interface `AdvancedUserInterface`.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        return ($user->getUsername() == $this->getUsername() &&
            $user->getPassword() == $this->getPassword() &&
            $user->getSalt() == $this->getSalt());
    }


    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
            $this->type,
            $this->status
        ));
    }

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
            $this->type,
            $this->status
            ) = unserialize($serialized);
    }


    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'email' => $this->getEmail(),
            'birthday' => ''
        );
    }

    public function getEncoderName()
    {
        if (strlen($this->password) == 40) {
            return 'legacy';
        }
        return 'default';
    }

    /**
     * @param $date
     */
    public function setJalaliBirthday($date)
    {
        $this->jalaliBirthday = $date;
    }

    /**
     * @return string
     */
    public function getJalaliBirthday()
    {
        return $this->jalaliBirthday;
    }

    /**
     * Set lastSeen
     *
     * @param \DateTime $lastSeen
     *
     * @return User
     */
    public function setLastSeen($lastSeen)
    {
        $this->lastSeen = $lastSeen;

        return $this;
    }

    /**
     * Get lastSeen
     *
     * @return \DateTime
     */
    public function getLastSeen()
    {
        return $this->lastSeen;
    }

    /**
     * @return Bool Whether the user is active or not
     */
    public function isActiveNow()
    {
        // Delay during wich the user will be considered as still active
        $delay = new \DateTime('2 minutes ago');

        return ($this->getLastSeen() > $delay);
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return User
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set nationalCode
     *
     * @param string $nationalCode
     *
     * @return User
     */
    public function setNationalCode($nationalCode)
    {
        $this->nationalCode = $nationalCode;

        return $this;
    }

    /**
     * Get nationalCode
     *
     * @return string
     */
    public function getNationalCode()
    {
        return $this->nationalCode;
    }


    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Add province
     *
     * @param \SmartCity\GeoBundle\Entity\Provinces $province
     *
     * @return User
     */
    public function addProvince(\SmartCity\GeoBundle\Entity\Province $province)
    {
        $this->provinces[] = $province;

        return $this;
    }

    /**
     * Remove province
     *
     * @param \SmartCity\GeoBundle\Entity\Provinces $province
     */
    public function removeProvince(\SmartCity\GeoBundle\Entity\Province $province)
    {
        $this->provinces->removeElement($province);
    }

    /**
     * Get provinces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProvinces()
    {
        return $this->provinces;
    }

}
