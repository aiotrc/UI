<?php
namespace SmartCity\UserBundle\Entity\Constants;

use Doctrine\ORM\Mapping as ORM;

class UserConstants
{   

    // sex
    const SEX_MALE = "MALE";
    const SEX_FEMALE = "FEMALE";

    // locale
    const PERSIAN = 'fa';
    const ENGLISH = 'en';

    // type
    const TYPE_BACKEND = "BACKEND";
    const TYPE_FRONTEND = "FRONTEND";

    // status
    const STATUS_ACTIVE = "ACTIVE";
    const STATUS_DEACTIVE = "DEACTIVE";
    const STATUS_LOCKED = "LOCKED";

    // images path
    const USER_PROFILE_IMAGE_PATH = "/uploads/images/user-profiles/";
    const USER_PROFILE_IMAGE_TYPE ='user_profile_image';

    // roles
    const ROLE_SUPER_ADMIN = "SUPER_ADMIN";
    const ROLE_ADMIN = "ADMIN";

    // forget password
    const FORGET_PASSWORD_MAXIMUM_TRIES = 10;
    const FORGET_PASSWORD_LIFECYCLE = '+10 min';
    const FORGET_PASSWORD_TYPE_SMS = 'SMS';
    const FORGET_PASSWORD_TYPE_EMAIL = 'EMAIL';

    public static $user_sexes = array(
        self::SEX_MALE => "label.user.sex.male",
        self::SEX_FEMALE => "label.user.sex.female",
    );

    public static $user_locales = array(
        self::PERSIAN => "label.locale.fa",
        self::ENGLISH => "label.locale.en",
    );

    public static $user_types = array(
        self::TYPE_BACKEND => "Backend User",
        self::TYPE_FRONTEND => "Frontend User",
    );

    public static $user_statuses = array(
        self::STATUS_ACTIVE => "label.user.status.active",
        self::STATUS_DEACTIVE => "label.user.status.deactive",
        self::STATUS_LOCKED => "label.user.status.locked",
    );

    public static $user_roles = array(
        self::ROLE_SUPER_ADMIN => 'label.role.super_admin',
        self::ROLE_ADMIN => 'label.role.admin',
    );

}
