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

}