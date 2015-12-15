<?php declare(strict_types=1);

namespace Application\Http\Response;

use Application\Util\Traits\GetterTrait;

/**
 * @package    Application
 * @subpackage Application\Http\Response
 * @object     Application\Http\Response\Status
 * @uses       Application\Util\Traits\GetterTrait
 * @author     Kerem! <kerem@Application>
 */
final class Status
{
    /**
     * Setter/Getter.
     * @object Application\Util\Traits\GetterTrait
     */
    use GetterTrait;

    /**
     * Default code.
     * @const int
     */
    const DEFAULT_CODE = 200;

    /**
     * Default text.
     * @const string
     */
    const DEFAULT_TEXT = 'OK';

    /**
     * Status code.
     * @var int
     */
    private $code = 0;

    /**
     * Status text.
     * @var string
     */
    private $text = '';

    /**
     * Status codes/texts.
     * @var array
     */
    private static $statuses = [
        // informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',

        // success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',

        // client error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // server error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded',
    ];

    /**
     * Object constructor.
     *
     * @param int    $code
     * @param string $text
     */
    final public function __construct(int $code = 0, string $text = '') {
        $this->code = $code;
        $this->text = $text;
    }

    /**
     * Set code.
     *
     * @param  int $code
     * @return void
     */
    final public function setCode(int $code): self {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code.
     *
     * @return int
     */
    final public function getCode(): int {
        return $this->code;
    }

    /**
     * Set text.
     *
     * @param  string $text
     * @return void
     */
    final public function setText(string $text): self {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text.
     *
     * @param  int $code
     * @return string
     */
    final public function getText(): string {
        return $this->text;
    }

    /**
     * Get status code and text.
     *
     * @return string
     */
    final public function getStatus(): string {
        return sprintf('%d %s', $this->code, $this->text);
    }

    /**
     * Get statuses.
     *
     * @return array
     */
    final public static function getStatuses(): array {
        return self::$statuses;
    }

    /**
     * Get status text by code.
     *
     * @param  int $code
     * @return string
     */
    final public static function getStatusText(int $code): string {
        return isset(self::$statuses[$code])
            ? self::$statuses[$code] : '';
    }

    /**
     * Get status code by text
     *
     * @param  string $text
     * @return int
     */
    final public static function getStatusCode(string $text): int {
        $codes = array_flip(self::$statuses);
        return isset($codes[$text])
            ? $codes[$text] : 0;
    }
}
