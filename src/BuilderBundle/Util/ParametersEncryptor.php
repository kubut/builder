<?php
namespace BuilderBundle\Util;

/**
 * Class ParametersEncryptor
 *
 * @package BuilderBundle\Util
 */
class ParametersEncryptor
{
    /** @var string */
    private static $secret = 'mEM%dseEvYaT5xT\3/B^y59n';

    /**
     * @param string|array $parameters
     *
     * @return string
     */
    public static function encrypt($parameters)
    {
        $encrypted = mcrypt_encrypt(MCRYPT_3DES, self::$secret, json_encode($parameters), MCRYPT_MODE_ECB);

        return self::base64UrlEncode($encrypted);
    }

    /**
     * @param string $encrypted
     *
     * @return string
     */
    public static function decrypt($encrypted)
    {
        $decrypted = mcrypt_decrypt(MCRYPT_3DES, self::$secret, self::base64UrlDecode($encrypted), MCRYPT_MODE_ECB);

        return json_decode(trim($decrypted), true);
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private static function base64UrlDecode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}