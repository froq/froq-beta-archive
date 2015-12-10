<?php
namespace Application;

use \Application\Application\Config;

final class Application
{
    private $config;

    final public function __construct(Config $config) {
        $this->config = $config;

        // check request count
        $maxRequest = $config->get('security.maxRequest');
        if ($maxRequest && count($_REQUEST) > $maxRequest) {
            $this->halt('429 Too Many Requests');
        }
        // check user agent
        $allowEmptyUserAgent = $config->get('security.allowEmptyUserAgent');
        if ($allowEmptyUserAgent === false && (
            !isset($_SERVER['HTTP_USER_AGENT']) || !trim($_SERVER['HTTP_USER_AGENT']))) {
            $this->halt('400 Bad Request');
        }
        // check client host
        $hosts = $config->get('app.hosts');
        if (!empty($hosts) && (
            !isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $hosts))) {
            $this->halt('400 Bad Request');
        }
        // check file extension
        $allowFileExtensionSniff = $config->get('security.allowFileExtensionSniff');
        if ($allowFileExtensionSniff === false &&
            preg_match('~\.(p[hyl]p?|rb|cgi|cf[mc]|p(pl|lx|erl)|aspx?)$~i',
                parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
            $this->halt('400 Bad Request');
        }
        // check service load
        if (sys_getloadavg()[0] > $config->get('app.loadAvg')) {
            $this->halt('503 Service Unavailable');
        }
    }

    final public function halt($header = null) {
        if ($header) {
            header(sprintf('%s %s', $_SERVER['SERVER_PROTOCOL'], $header));
        }
        header('Connection: close');
        header('Content-Type: none');
        header('Content-Length: 0');
        header_remove('X-Powered-By');
        exit(1);
    }
}
