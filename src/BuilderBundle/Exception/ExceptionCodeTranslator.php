<?php
namespace BuilderBundle\Exception;

use BuilderBundle\Exception\ExceptionCode;

/**
 * Class ExceptionCodeTranslator is dedicated for all api exceptions for user.
 * Dictionaries are in Resources\translations - feel free to extend the files (all languages versions)
 *
 * @package ED\AppBundle\Exception
 */
class ExceptionCodeTranslator
{
    const FIELD_NAME = '%field_name%';

    public static $transCodes = [
        ExceptionCode::GENERAL_ERROR => 'general',

    ];

    /**
     * @param integer $code
     *
     * @return mixed
     */
    public static function getTranslatorCode($code)
    {
        return isset(self::$transCodes[$code]) ? self::$transCodes[$code] : self::$transCodes[ExceptionCode::GENERAL_ERROR];
    }
}