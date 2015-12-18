<?php declare(strict_types=1);
namespace Application\Encryption;

/**
 * @package    Application
 * @subpackage Application\Encryption
 * @object     Application\Encryption\Salt
 * @author     Kerem! <qeremy@gmail>
 */
final class Salt
{
    /**
     * Salt length.
     * @const int
     */
    const LENGTH = 128;

    /**
     * Salt type.
     * @const int
     */
    const TYPE_SELF = 1,
          TYPE_URANDOM = 2;

    /**
     * Salt chars.
     * @var string
     */
    private static $chars = './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Generate a salt.
     *
     * @param  int $type
     * @param  int $length
     * @return string
     */
    final public static function generate(int $type = self::TYPE_URANDOM,
            int $length = self::LENGTH, bool $crop = true): string {
        // use urandom method (default)
        if ($type == self::TYPE_URANDOM) {
            $salt = base64_encode(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
            if ($crop) {
                $salt = substr($salt, 0, $length);
            }
        }
        // use self method
        elseif ($type == self::TYPE_SELF) {
            $salt = '';
            for ($i = 0; $i < $length; $i++) {
                $salt .= self::$chars[mt_rand(0, 63)];
            }
        }

        return $salt;
    }
}
