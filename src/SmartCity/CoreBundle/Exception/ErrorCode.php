<?php
// src/BodoFood/Bundle/Exception/ErrorCode.php
namespace BodoFood\Bundle\Exception;

use Monolog\Logger;

/**
 * Class ErrorCode
 * @package BodoFood\Bundle\Services
 */
class ErrorCode
{
    const INTERNAL_SERVER_ERROR = 500;
    const VALIDATION_FAILED = 501;
    const INVALID_ARGUMENT = 502;
    const ACCESS_DENIED = 503;
    const RESOURCE_NOT_FOUND = 504;

    const MAINTENANCE = 1000;
    const FORCE_UPDATE = 1001;
    const SECURITY_VIOLATION = 1002;
    const USER_NOT_LOGGED_IN = 1003;
    const DATA_NOT_JSON = 1004;
    const WRONG_USERNAME_OR_PASSWORD = 1005;
    const USER_ALREADY_EXIST = 1006;
    const LOGIN_ERROR = 1007;
    const WRONG_PASSWORD = 1008;
    const EMAIL_ALREADY_EXIST = 1009;
    const USER_INSUFFICIENT_CREDIT = 1010;
    const USER_NOT_FOUND = 1011;
    const CITY_NOT_FOUND = 1012;
    const ADDRESS_NOT_FOUND = 1013;
    const PERMISSION_DENIED = 1014;
    const SHOULD_BE_NUMERIC = 1015;
    const LOGOUT_ERROR = 1016;
    const EMAIL_NOT_FOUND = 1017;
    const INVALID_EMAIL = 1018;
    const VENDOR_NOT_FOUND = 1019;
    const CELLPHONE_IS_NOT_IN_CORRECT_FORMAT = 1020;
    const PHONE_IS_NOT_IN_CORRECT_FORMAT = 1021;
    const WRONG_PAY_TYPE = 1022;
    const ORDER_NOT_FOUND = 1023;
    const USER_IS_INACTIVE = 1024;
    const INVALID_VOUCHER = 1025; // TODO: WTF is this?
    const MATCH_IS_INACTIVE = 1026;
    const MATCH_HAS_SCORED_BEFORE = 1027;
    const MENU_CATEGORY_NOT_FOUND = 1088;
    const ORDER_NOT_ACCEPTED = 1089;

    // TODO: WTF is the following codes?? they should be started from 1026 not 0 :|
    const RESULT_VOUCHER_VALID = 0; // 1026
    const RESULT_VOUCHER_NOT_FOUND = 1; // 1027
    const RESULT_VOUCHER_NOT_VALID = 2; // 1028
    const RESULT_VOUCHER_PERMISSION_DENIED = 3; // 1029
    const RESULT_USED_BEFORE = 4; // 1030
    const RESULT_VOUCHER_EXPIRED = 5; // 1031
    const RESULT_VOUCHER_RAN_OUT = 6; // 1032
    const RESULT_VOUCHER_MIN_ORDER_VALUE_VIOLATION = 7; // 1033
    const RESULT_VOUCHER_MAX_ORDER_VALUE_VIOLATION = 8; // 1034
    const RESULT_AFTER_ORDER_GIFT_VOUCHER_NOT_FOUND = 9;
    const RESULT_VOUCHER_PAYMENT_TYPE_INVALID = 10;
    const RESULT_VOUCHER_TELEGRAM_REPETITIVE = 11;
    const RESULT_VOUCHER_TEMPORARY_EXPIRED = 12;
    const RESULT_VOUCHER_VENDOR_NOT_ALLOWED = 13;
    const RESULT_VOUCHER_USED_BEFORE = 14;
    const RESULT_VOUCHER_PLATFORM_NOT_MATCH = 15;
    const RESULT_VOUCHER_NOT_ACTIVE = 16;
    const RESULT_VOUCHER_PLATFORM_JUST_ANDROID = 17;
    const RESULT_VOUCHER_PLATFORM_JUST_IOS = 18;
    const RESULT_VOUCHER_PLATFORM_JUST_WEB = 19;
    const RESULT_VOUCHER_PLATFORM_JUST_MOBILE = 20;

    const PAYMENT_NOT_FOUND = 1035;
    const PAYMENT_IS_IN_PROCESSING = 1036;
    const PAYMENT_TYPE_IS_NOT_ALLOWED = 1037;
    const PAYMENT_REFERENCE_ID_NOT_FOUND = 1038;
    const GYP_CODE_USED_BEFORE = 1039;
    const GYP_IS_NOT_ACTIVE = 1040;
    const GYP_CELL_NOT_FOUND = 1041;
    const GYP_CELL_USED_BEFORE = 1042;
    const INVALID_GYP = 1043;
    const GYP_CODE_NOT_FOUND = 1044;
    const BANK_IS_DISABLE = 1045;
    const NOT_SUPPORTED = 1046;
    const RESET_PASSWORD_EXCEEDED_TRIES = 1047;
    const MAINTENANCE_MODE = 1048;
    const VENDOR_INACTIVE = 1049;
    const VENDOR_REVIEW_NOT_FOUND = 1050;
    const PRODUCT_VARIATION_NOT_FOUND = 1051;
    const TELEGRAM_BOT_NOT_FOUND = 1052;
    const SNAKES_LADDERS_USER_NOT_FOUND = 1053;
    const SNAKES_LADDERS_USER_WON = 1054;
    const PREDICTION_GIFT_CODE_NOT_FOUND = 1055;
    const PREDICTION_GIFT_CODE_USED = 1056;
    const CELLPHONE_ALREADY_EXIST = 1057;
    const PREDICTION_GIFT_CODE_EXPIRED = 1058;

    const VMS_ORDER_ALREADY_ACCEPTED = 1059;
    const VMS_ORDER_ALREADY_REJECTED = 1060;

    const DECLINE_REASON_NOT_FOUND = 1061;

    const PAYMENT_INFO_NOT_FOUND = 1062;

    const IMAGE_NOT_FOUND = 1063;
    const COMMENT_NOT_FOUND = 1064;
    const LIKE_NOT_FOUND = 1065;

    const LEVEL_RANKING_NOT_FOUND = 1066;

    const VERIFICATION_CODE_NOT_FOUND = 1067;

    const VMS_ORDER_ALREADY_PICKED = 1068;

    const RESULT_VIP_USER_ALREADY_EXIST = 1069;

    const VENDOR_RECOMMENDATION_NOT_FOUND = 1070;

    const ZOODFOOD_BIRTH_DAY_INACTIVE = 1071;
    const ZOODFOOD_BIRTH_DAY_USER_TURNS_EXCEEDED = 1072;

    const RPS_USER_TURNS_EXCEEDED = 1073;
    const RPS_USER_INVALID_MOVE = 1074;
    const RPS_USER_GAME_FINISHED = 1075;
    const RPS_USER_TRY_AGAIN = 1076;
    const RPS_GAME_EXPIRED = 1077;
    const RPS_GAME_NOT_ORDER = 1079;

    const VIP_CODE_NOT_FOUND = 1078;
    const VENDOR_NOT_OPEN = 1079;
    const ORDER_LONG_DISTANCE = 1080;
    const LAT_LON_NOT_SET = 1081;

    const VENDOR_REVIEW_HAS_REPLY = 1082;

    const ADDRESS_IS_EMPTY = 1083;
    const PASSWORD_REQUIRED = 1084;
    const BANK_IS_NOT_SELECTED = 1085;

    const GYP_CODE_EXPIRED = 1086;

    const ELECTION_USER_TURNS_EXCEEDED = 2087;
    const ELECTION_USER_TURNS_NOT_FOUND = 2088;
    const ELECTION_CELL_NOT_FOUND = 2089;
    const ORDER_LONG_DISTANCE_IGNORE_BY_RESTAURANT = 2090;
    const ORDER_CASH_NOT_ALLOWED = 2091;

    const GOT_NOT_FOUND = 2092;
    const GOT_ALREADY_VOTED = 2093;

    public static $messages = array(
        self::INTERNAL_SERVER_ERROR => 'errors.internal_server_error',
        self::VALIDATION_FAILED => 'errors.validation_failed',
        self::INVALID_ARGUMENT => 'errors.invalid_argument',
        self::ACCESS_DENIED => 'errors.access_denied',
        self::RESOURCE_NOT_FOUND => 'errors.resource_not_found',
        self::MAINTENANCE => 'errors.maintenance',
        self::FORCE_UPDATE => 'errors.force_update',
        self::DATA_NOT_JSON => 'errors.data_not_json',
        self::USER_NOT_LOGGED_IN => 'errors.user_not_logged_in',
        self::WRONG_USERNAME_OR_PASSWORD => 'errors.wrong_username_or_password',
        self::USER_ALREADY_EXIST => 'errors.user_already_exist',
        self::LOGIN_ERROR => 'errors.login_error',
        self::WRONG_PASSWORD => 'errors.password.wrong',
        self::EMAIL_ALREADY_EXIST => 'error.email_exists',
        self::USER_INSUFFICIENT_CREDIT => 'errors.user.insufficient_credit',
        self::SECURITY_VIOLATION => 'errors.security.violation',
        self::USER_NOT_FOUND => 'errors.user.not_found',
        self::CITY_NOT_FOUND => 'errors.city.not_found',
        self::ADDRESS_NOT_FOUND => 'errors.address.not_found',
        self::PERMISSION_DENIED => 'errors.permission_denied',
        self::SHOULD_BE_NUMERIC => 'errors.should_be_numeric',
        self::LOGOUT_ERROR => 'errors.logout_error',
        self::EMAIL_NOT_FOUND => 'errors.user_not_found',
        self::INVALID_EMAIL => 'errors.invalid_email',
        self::VENDOR_NOT_FOUND => 'errors.vendor_not_found',
        self::CELLPHONE_IS_NOT_IN_CORRECT_FORMAT => 'errors.cellphone_is_not_in_correct_format',
        self::PHONE_IS_NOT_IN_CORRECT_FORMAT => 'errors.phone_is_not_in_correct_format',
        self::WRONG_PAY_TYPE => 'errors.wrong_pay_type',
        self::ORDER_NOT_FOUND => 'errors.order.not_found',
        self::USER_IS_INACTIVE => 'errors.user.inactive',
        self::RESULT_VOUCHER_NOT_FOUND => 'errors.voucher.not_found',
        self::RESULT_VOUCHER_NOT_VALID => 'errors.voucher.not_valid',
        self::RESULT_VOUCHER_PERMISSION_DENIED => 'errors.voucher.permission_denied',
        self::RESULT_USED_BEFORE => 'errors.voucher.used_before',
        self::RESULT_VOUCHER_EXPIRED => 'errors.voucher.expired',
        self::RESULT_VOUCHER_RAN_OUT => 'errors.voucher.ran_out',
        self::RESULT_VOUCHER_MIN_ORDER_VALUE_VIOLATION => 'errors.voucher.min_order_value_violation',
        self::RESULT_VOUCHER_MAX_ORDER_VALUE_VIOLATION => 'errors.voucher.max_order_value_violation',
        self::RESULT_VOUCHER_VALID => 'labels.voucher.valid', // TODO: errors instead of labels
        self::PAYMENT_NOT_FOUND => 'errors.payment.not_found',
        self::PAYMENT_IS_IN_PROCESSING => 'errors.payment.is_in_processing',
        self::PAYMENT_TYPE_IS_NOT_ALLOWED => 'errors.payment_type.not_allowed',
        self::PAYMENT_REFERENCE_ID_NOT_FOUND => 'errors.payment.reference_id_not_found',
        self::GYP_CODE_USED_BEFORE => 'errors.gyp.code.used_before',
        self::GYP_IS_NOT_ACTIVE => 'errors.gyp.not_active',
        self::GYP_CELL_NOT_FOUND => 'errors.gyp.cell.not_found',
        self::GYP_CELL_USED_BEFORE => 'errors.gyp.cell.used_before',
        self::INVALID_GYP => 'errors.gyp.invalid',
        self::GYP_CODE_NOT_FOUND => 'errors.gyp.code.not_found',
        self::BANK_IS_DISABLE => 'errors.bank.disabled',
        self::NOT_SUPPORTED => 'errors.forget.not_supported',
        self::RESET_PASSWORD_EXCEEDED_TRIES => 'errors.forget.exceeded',
        self::MAINTENANCE_MODE => 'errors.maintenance.message',
        self::VENDOR_INACTIVE => 'errors.vendor_inactive',
        self::MATCH_IS_INACTIVE => 'errors.match.is_inactive',
        self::MATCH_HAS_SCORED_BEFORE => 'errors.match.has_scored_before',
        self::VENDOR_REVIEW_NOT_FOUND => 'errors.math.vendor_review_not_found',
        self::PRODUCT_VARIATION_NOT_FOUND => 'errors.product.variation_not_found',
        self::TELEGRAM_BOT_NOT_FOUND => 'errors.telegram.bot_not_found',
        self::MENU_CATEGORY_NOT_FOUND => 'errors.menu.menu_category_not_found',
        self::SNAKES_LADDERS_USER_NOT_FOUND => 'errors.snakes_ladders.user_not_registered',
        self::SNAKES_LADDERS_USER_WON => 'errors.snakes_ladders.user_won',
        self::PREDICTION_GIFT_CODE_NOT_FOUND => 'errors.prediction.code.not_found',
        self::PREDICTION_GIFT_CODE_USED => 'errors.prediction.code.used',
        self::CELLPHONE_ALREADY_EXIST => 'error.cellphone_exists', //'errors.user_cellphone_exist',
        self::PREDICTION_GIFT_CODE_EXPIRED => 'errors.prediction.code.expired',
        self::VMS_ORDER_ALREADY_ACCEPTED => 'errors.vms.order.already.accepted',
        self::VMS_ORDER_ALREADY_REJECTED => 'errors.vms.order.already.rejected',
        self::DECLINE_REASON_NOT_FOUND => 'errors.decline_reason.not_found',
        self::PAYMENT_INFO_NOT_FOUND => 'errors.payment_info.not_found',
        self::RESULT_VOUCHER_PAYMENT_TYPE_INVALID => 'errors.vouchers_payment_type_invalid',
        self::RESULT_VOUCHER_TELEGRAM_REPETITIVE => 'errors.voucher_telegram_repetitive',
        self::RESULT_VOUCHER_TEMPORARY_EXPIRED => 'errors.voucher_temporary_expired',
        self::PAYMENT_INFO_NOT_FOUND => 'errors.payment_info.not_found',
        self::IMAGE_NOT_FOUND => 'errors.image.not_found',
        self::COMMENT_NOT_FOUND => 'errors.comment.not_found',
        self::LIKE_NOT_FOUND => 'errors.like.not_found',
        self::LEVEL_RANKING_NOT_FOUND => 'errors.level_ranking_not_found',
        self::VERIFICATION_CODE_NOT_FOUND => 'errors.verification_code_not_found',
        self::VMS_ORDER_ALREADY_PICKED => 'errors.vms.order.already.picked',
        self::RESULT_VIP_USER_ALREADY_EXIST => 'errors.vip.already.exist',
        self::VENDOR_RECOMMENDATION_NOT_FOUND => 'errors.vendor_recommendation_not_found',
        self::ZOODFOOD_BIRTH_DAY_INACTIVE => 'errors.zoodfood_birth_day_inactive',
        self::ZOODFOOD_BIRTH_DAY_USER_TURNS_EXCEEDED => 'errors.zoodfood_birth_day_user_turns_exceeded',
        self::VIP_CODE_NOT_FOUND => 'errors.vip.code.not_found',
        self::ORDER_LONG_DISTANCE => 'فاصله شما تا رستوران بیشتر از ۳۰۰۰ متر است',
        self::LAT_LON_NOT_SET => 'موقعیت شما روی نقشه مشخص نیست',
        self::VENDOR_REVIEW_HAS_REPLY => 'نظر مشتری قبلا پاسخ داده شده است',
        self::ADDRESS_IS_EMPTY => 'آدرس وارد شده درست نیست',
        self::PASSWORD_REQUIRED => 'errors.password.required',
        self::RESULT_VOUCHER_VENDOR_NOT_ALLOWED => 'errors.voucher.vendor_not_allowed',
        self::RESULT_VOUCHER_USED_BEFORE => 'errors.voucher.used',
        self::RESULT_VOUCHER_PLATFORM_NOT_MATCH => 'errors.voucher.platform.not_support',
        self::RESULT_VOUCHER_PLATFORM_JUST_ANDROID => 'errors.voucher.platform.just_android',
        self::RESULT_VOUCHER_PLATFORM_JUST_IOS => 'errors.voucher.platform.just_ios',
        self::RESULT_VOUCHER_PLATFORM_JUST_WEB => 'errors.voucher.platform.just_web',
        self::RESULT_VOUCHER_PLATFORM_JUST_MOBILE => 'errors.voucher.platform.just_mobile',
        self::ORDER_NOT_ACCEPTED => 'errors.order.not_accepted',
        self::BANK_IS_NOT_SELECTED =>'errors.bank_is_not_selected',
        self::ELECTION_USER_TURNS_EXCEEDED =>'errors.campaign.election.turns.exceeded',
        self::ELECTION_USER_TURNS_NOT_FOUND =>'errors.campaign.election.turns.not.found',
        self::ELECTION_CELL_NOT_FOUND =>'errors.campaign.election.cell.not.found',
        self::ORDER_LONG_DISTANCE_IGNORE_BY_RESTAURANT => 'errors.order.address_distance_ignored_by_restaurant',
        self::ORDER_CASH_NOT_ALLOWED => 'errors.order_cash_not_allowed',
        self::GOT_NOT_FOUND => 'errors.got_not_found',
        self::GOT_ALREADY_VOTED => 'errors.got_already_voted'
    );

    public static $levels = array(
        self::INTERNAL_SERVER_ERROR => Logger::CRITICAL,
        self::VALIDATION_FAILED => Logger::ERROR,
        self::INVALID_ARGUMENT => Logger::ERROR,
        self::ACCESS_DENIED => Logger::ERROR,
        self::RESOURCE_NOT_FOUND => Logger::INFO,
        self::MAINTENANCE => Logger::INFO,
        self::FORCE_UPDATE => Logger::INFO,
        self::SECURITY_VIOLATION => Logger::ERROR,
        self::USER_NOT_LOGGED_IN => Logger::ERROR,
        self::DATA_NOT_JSON => Logger::CRITICAL,
        self::WRONG_USERNAME_OR_PASSWORD => Logger::ERROR,
        self::USER_ALREADY_EXIST => Logger::ERROR,
        self::LOGIN_ERROR => Logger::CRITICAL,
        self::WRONG_PASSWORD => Logger::INFO,
        self::EMAIL_ALREADY_EXIST => Logger::INFO,
        self::USER_INSUFFICIENT_CREDIT => Logger::INFO,
        self::USER_NOT_FOUND => Logger::INFO,
        self::CITY_NOT_FOUND => Logger::CRITICAL,
        self::ADDRESS_NOT_FOUND => Logger::CRITICAL,
        self::PERMISSION_DENIED => Logger::CRITICAL,
        self::SHOULD_BE_NUMERIC => Logger::CRITICAL,
        self::LOGOUT_ERROR => Logger::CRITICAL,
        self::EMAIL_NOT_FOUND => Logger::INFO,
        self::INVALID_EMAIL => Logger::INFO,
        self::VENDOR_NOT_FOUND => Logger::ERROR,
        self::CELLPHONE_IS_NOT_IN_CORRECT_FORMAT => Logger::INFO,
        self::PHONE_IS_NOT_IN_CORRECT_FORMAT => Logger::INFO,
        self::WRONG_PAY_TYPE => Logger::CRITICAL,
        self::ORDER_NOT_FOUND => Logger::CRITICAL,
        self::USER_IS_INACTIVE => Logger::INFO,
        self::RESULT_VOUCHER_NOT_FOUND => Logger::INFO,
        self::RESULT_VOUCHER_NOT_VALID => Logger::INFO,
        self::RESULT_VOUCHER_PERMISSION_DENIED => Logger::INFO,
        self::RESULT_USED_BEFORE => Logger::INFO,
        self::RESULT_VOUCHER_EXPIRED => Logger::INFO,
        self::RESULT_VOUCHER_RAN_OUT => Logger::INFO,
        self::RESULT_VOUCHER_MIN_ORDER_VALUE_VIOLATION => Logger::INFO,
        self::RESULT_VOUCHER_MAX_ORDER_VALUE_VIOLATION => Logger::INFO,
        self::RESULT_VOUCHER_VALID => Logger::INFO,
        self::PAYMENT_NOT_FOUND => Logger::INFO,
        self::PAYMENT_IS_IN_PROCESSING => Logger::CRITICAL,
        self::PAYMENT_TYPE_IS_NOT_ALLOWED => Logger::ERROR,
        self::PAYMENT_REFERENCE_ID_NOT_FOUND => Logger::CRITICAL,
        self::GYP_CODE_USED_BEFORE => Logger::INFO,
        self::GYP_IS_NOT_ACTIVE => Logger::ERROR,
        self::GYP_CELL_NOT_FOUND => Logger::CRITICAL,
        self::GYP_CELL_USED_BEFORE => Logger::ERROR,
        self::INVALID_GYP => Logger::CRITICAL,
        self::GYP_CODE_NOT_FOUND => Logger::INFO,
        self::BANK_IS_DISABLE => Logger::ERROR,
        self::NOT_SUPPORTED => Logger::INFO,
        self::RESET_PASSWORD_EXCEEDED_TRIES => Logger::ERROR,
        self::MAINTENANCE_MODE => Logger::ERROR,
        self::VENDOR_INACTIVE => Logger::ERROR,
        self::MATCH_IS_INACTIVE => Logger::ERROR,
        self::MATCH_HAS_SCORED_BEFORE => Logger::ERROR,
        self::VENDOR_REVIEW_NOT_FOUND => Logger::ERROR,
        self::PRODUCT_VARIATION_NOT_FOUND => Logger::ERROR,
        self::TELEGRAM_BOT_NOT_FOUND => Logger::ERROR,
        self::MENU_CATEGORY_NOT_FOUND => Logger::ERROR,
        self::SNAKES_LADDERS_USER_NOT_FOUND => Logger::INFO,
        self::SNAKES_LADDERS_USER_WON => Logger::INFO,
        self::PREDICTION_GIFT_CODE_NOT_FOUND => Logger::INFO,
        self::PREDICTION_GIFT_CODE_USED => Logger::INFO,
        self::CELLPHONE_ALREADY_EXIST => Logger::ERROR,
        self::PREDICTION_GIFT_CODE_EXPIRED => Logger::INFO,
        self::VMS_ORDER_ALREADY_ACCEPTED => Logger::INFO,
        self::VMS_ORDER_ALREADY_REJECTED => Logger::INFO,
        self::DECLINE_REASON_NOT_FOUND => Logger::INFO,
        self::PAYMENT_INFO_NOT_FOUND => Logger::ERROR,
        self::RESULT_VOUCHER_PAYMENT_TYPE_INVALID => Logger::ERROR,
        self::RESULT_VOUCHER_TELEGRAM_REPETITIVE => Logger::ERROR,
        self::RESULT_VOUCHER_TEMPORARY_EXPIRED => Logger::ERROR,
        self::PAYMENT_INFO_NOT_FOUND => Logger::ERROR,
        self::IMAGE_NOT_FOUND => Logger::ERROR,
        self::COMMENT_NOT_FOUND => Logger::ERROR,
        self::LIKE_NOT_FOUND => Logger::ERROR,
        self::LEVEL_RANKING_NOT_FOUND => Logger::ERROR,
        self::VERIFICATION_CODE_NOT_FOUND => Logger::ERROR,
        self::VMS_ORDER_ALREADY_PICKED => Logger::ERROR,
        self::RESULT_VIP_USER_ALREADY_EXIST => Logger::ERROR,
        self::VENDOR_RECOMMENDATION_NOT_FOUND => Logger::ERROR,
        self::ZOODFOOD_BIRTH_DAY_INACTIVE => Logger::ERROR,
        self::ZOODFOOD_BIRTH_DAY_USER_TURNS_EXCEEDED => Logger::ERROR,
        self::VIP_CODE_NOT_FOUND => Logger::ERROR,
        self::RPS_USER_TURNS_EXCEEDED => Logger::ERROR,
        self::RPS_USER_INVALID_MOVE => Logger::ERROR,
        self::RPS_USER_GAME_FINISHED => Logger::ERROR,
        self::RPS_USER_TRY_AGAIN => Logger::ERROR,
        self::RPS_GAME_EXPIRED => Logger::ERROR,
        self::RPS_GAME_NOT_ORDER => Logger::ERROR,
        self::VENDOR_NOT_OPEN => Logger::ERROR,
        self::ORDER_LONG_DISTANCE => Logger::ERROR,
        self::LAT_LON_NOT_SET => Logger::ERROR,
        self::VENDOR_REVIEW_HAS_REPLY => Logger::ERROR,
        self::ADDRESS_IS_EMPTY => Logger::ERROR,
        self::RESULT_VOUCHER_VENDOR_NOT_ALLOWED => Logger::ERROR,
        self::RESULT_VOUCHER_USED_BEFORE => Logger::ERROR,
        self::RESULT_VOUCHER_PLATFORM_NOT_MATCH => Logger::ERROR,
        self::RESULT_VOUCHER_PLATFORM_JUST_ANDROID => Logger::ERROR,
        self::RESULT_VOUCHER_PLATFORM_JUST_IOS => Logger::ERROR,
        self::RESULT_VOUCHER_PLATFORM_JUST_WEB => Logger::ERROR,
        self::RESULT_VOUCHER_PLATFORM_JUST_MOBILE => Logger::ERROR,
        self::ORDER_NOT_ACCEPTED => Logger::ERROR,
        self::BANK_IS_NOT_SELECTED => Logger::ERROR,
        self::ELECTION_USER_TURNS_EXCEEDED => Logger::ERROR,
        self::ELECTION_USER_TURNS_NOT_FOUND => Logger::ERROR,
        self::ELECTION_CELL_NOT_FOUND => Logger::ERROR,
        self::ORDER_LONG_DISTANCE_IGNORE_BY_RESTAURANT => Logger::ERROR,
        self::ORDER_CASH_NOT_ALLOWED => Logger::ERROR,
        self::GOT_NOT_FOUND => Logger::ERROR,
        self::GOT_ALREADY_VOTED => Logger::ERROR
    );
}
