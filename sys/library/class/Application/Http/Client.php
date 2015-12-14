<?php
namespace Application\Http;

use \Application\Util\Traits\GetterTrait;

/**
 * @package    Application
 * @subpackage Application\Http
 * @object     Application\Http\Client
 * @uses       Application\Util\Traits\GetterTrait
 * @author     Kerem! <qeremy@gmail>
 */
final class Client
{
    /**
     * Setter/getter object.
     * @object Application\Util\Traits\GetterTrait
     */
    use GetterTrait;

    /**
     * Client IP.
     * @var string
     */
    private $ip;

    /**
     * Client locale.
     * @var string
     */
    private $locale;

    /**
     * Client language.
     * @var string
     */
    private $language;

    /**
     * Object constructor.
     */
    final public function __construct() {
        $app = app();

        // set ip
        $this->ip = ip();

        // set language
        $this->language = $app->config->get('app.language');
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($language, $app->config->get('app.languages'))) {
                $this->language = $language;
            }
        }

        // set locale
        $this->locale = sprintf('%s_%s', $this->language, strtoupper($this->language));
        if (!array_key_exists($this->locale, $app->config->get('app.locales'))) {
            $this->locale = $app->config->get('app.locale');
        }
    }
}
