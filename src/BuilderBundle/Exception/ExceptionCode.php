<?php
namespace BuilderBundle\Exception;

/**
 * Class ExceptionCode
 */
class ExceptionCode
{
    const GENERAL_ERROR = 400;

    const PERMISSION_DENIED = 666;
    const USER_NOT_UNIQUE = 667;
    const OMITTED_PARAMS = 668;
    const PASSWORD_TOO_SHORT = 669;
    const USER_NOT_EXIST = 670;
    const INVALID_JSON = 671;
    const VALIDATION_PROJECT_CONFIG_FILE = 672;
    const VALIDATION_PROJECT_NAME = 673;
    const VALIDATION_PROJECT_TOO_MANY = 674;
    const VALIDATION_PROJECT_PARAMS = 675;
    const PROJECT_NOT_EXIST = 676;

}