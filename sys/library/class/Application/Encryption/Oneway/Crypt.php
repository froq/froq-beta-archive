<?php declare(strict_types=1);
namespace Application\Encryption\Oneway;

/**
 * @package    Application
 * @subpackage Application\Encryption\Oneway
 * @object     Application\Encryption\Oneway\Crypt
 * @author     Kerem! <kerem@qeremy>
 */
final class Crypt
{
    /**
     * Default salt lentgh.
     * @const int
     */
    const SALT_LENGTH = 64;

    /**
     * Crypt input.
     * @var string
     */
    private $input;

    /**
     * Crypt salt format.
     * @var string
     */
    private $format = '$2y$10$%s$'; // blowfish

    /**
     * Crypt salt chars.
     * @var string
     */
    private $saltChars = './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Object constructor.
     *
     * @param string $input
     */
    final public function __construct(string $input) {
        $this->input = $input;
    }

    /**
     * Generate a salt.
     *
     * @param  int $length
     * @return string
     */
    final public function generateSalt(int $length = self::SALT_LENGTH): string {
        return substr(str_shuffle($this->saltChars), 0, $length);
    }

    /**
     * Generate a hash.
     *
     * @param  string $salt
     * @return string
     */
    final public function hash(string $salt = null): string {
        if ($salt == '') {
            $salt = $this->generateSalt();
        }

        return crypt($this->input, sprintf($this->format, $salt));
    }

    /**
     * Verify a hash.
     *
     * @param  string $hash
     * @return bool
     */
    final public function verify(string $hash): bool {
        return $hash == crypt($this->input, $hash);
    }
}
