<?php namespace Application;

use Application\Http\Uri\Uri,
    Application\Http\Uri\UriPath;
use Application\Service\ServiceAdapter,
    Application\Service\ServiceInterface;
use Application\Http\Request,
    Application\Http\Response;
use Application\Database\Database;
use Application\Util\Traits\SingleTrait,
    Application\Util\Traits\SetGetTrait;

final class Application
{
    use SingleTrait;
    use SetGetTrait;

    private $config;
    private $service;
    private $request, $response;
    private $db;

    final private function __construct() {
        $this->request = new Request();
        $this->db = new Database($this);
        // set app as global
        set_global('app', $this);
    }

    final public function run() {
        if (empty($this->config)) {
            throw new \RuntimeException('Call setConfig() first to get run application!');
        }

        // check request count
        $maxRequest = $this->config->get('security.maxRequest');
        if ($maxRequest && count($_REQUEST) > $maxRequest) {
            self::halt('429 Too Many Requests');
        }
        // check user agent
        $allowEmptyUserAgent = $this->config->get('security.allowEmptyUserAgent');
        if ($allowEmptyUserAgent === false && (
            !isset($_SERVER['HTTP_USER_AGENT']) || !trim($_SERVER['HTTP_USER_AGENT']))) {
            self::halt('400 Bad Request');
        }
        // check client host
        $hosts = $this->config->get('app.hosts');
        if (!empty($hosts) && (
            !isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $hosts))) {
            self::halt('400 Bad Request');
        }
        // check file extension
        $allowFileExtensionSniff = $this->config->get('security.allowFileExtensionSniff');
        if ($allowFileExtensionSniff === false &&
            preg_match('~\.(p[hyl]p?|rb|cgi|cf[mc]|p(pl|lx|erl)|aspx?)$~i',
                parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
            self::halt('400 Bad Request');
        }
        // check service load
        if (sys_getloadavg()[0] > $this->config->get('app.loadAvg')) {
            self::halt('503 Service Unavailable');
        }

        $serviceAdapter = new ServiceAdapter($this);
        if (!$serviceAdapter->isServiceExists()) {
            throw new Exception(sprintf(
                'Service not found! name: %s', $serviceAdapter->getServiceName()));
        }

        $this->service = $serviceAdapter->createService();
        $this->service->callMethodInit();

        $this->startOutputBuffer();
        $this->service->callMethodBefore();
        if ($this->service->isHome()) {
            print $this->service->callMethodMain();
        } else {
            print $this->service->callMethodInvoked();
        }
        $this->service->callMethodAfter();
        $this->endOutputBuffer();
    }

    final public function setConfig(Config $config) {
        $this->config = $config;
        return $this;
    }
    final public function getConfig() {
        return $this->config;
    }

    final public function setDefaults() {
        $cfg = ['locale'   => $this->config->get('app.locale'),
                'encoding' => $this->config->get('app.encoding'),
                'timezone' => $this->config->get('app.timezone'),
        ];
        // multibyte
        mb_internal_encoding($cfg['encoding']);
        // timezone
        date_default_timezone_set($cfg['timezone']);
        // default charset
        ini_set('default_charset', $cfg['encoding']);
        // locale stuff
        $locale = sprintf('%s.%s', $cfg['locale'], $cfg['encoding']);
        setlocale(LC_TIME, $locale);
        setlocale(LC_NUMERIC, $locale);
        setlocale(LC_MONETARY, $locale);
        return $this;
    }

    final public function startOutputBuffer() {
        ini_set('implicit_flush', 1);
        ini_set('zlib.output_compression', 0);
        ob_start();
    }

    final public function endOutputBuffer(callable $callable = null) {
        $output = '';
        while (ob_get_level()) {
            $output .= ob_get_clean();
        }
        print $output;
    }

    final private function halt($status = null) {
        if ($status) {
            $status = sprintf('%s %s', $_SERVER['SERVER_PROTOCOL'], $status);
            header($status);
        }
        header('Connection: close');
        header('Content-Type: none');
        header('Content-Length: 0');
        header_remove('X-Powered-By');
        exit(1);
    }
}
