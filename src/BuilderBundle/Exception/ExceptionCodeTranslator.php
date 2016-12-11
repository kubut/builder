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
        ExceptionCode::PERMISSION_DENIED => 'permission_denied',
        ExceptionCode::USER_NOT_UNIQUE => 'user.not_unique',
        ExceptionCode::USER_NOT_EXIST => 'user.not_exist',
        ExceptionCode::OMITTED_PARAMS => 'params.not_enough',
        ExceptionCode::PASSWORD_TOO_SHORT => 'validation.password',
        ExceptionCode::INVALID_JSON => 'validation.json',
        ExceptionCode::VALIDATION_PROJECT_CONFIG_FILE => 'validation.project.config_file',
        ExceptionCode::VALIDATION_PROJECT_NAME => 'validation.project.name',
        ExceptionCode::VALIDATION_PROJECT_TOO_MANY => 'validation.project.too_many',
        ExceptionCode::VALIDATION_PROJECT_PARAMS => 'validation.project.params',
        ExceptionCode::PROJECT_NOT_EXIST => 'validation.project.not_exist',
        ExceptionCode::CHECKLIST_NOT_EXIST => 'validation.checklist.not_exist',
        ExceptionCode::CHECKLIST_ITEM_NOT_EXIST => 'validation.checklist_item.not_exist',
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