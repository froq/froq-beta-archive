<?php namespace Application;

use Application\Application\Config;
use Application\Http\Uri\Uri;
use Application\Http\Request,
    Application\Http\Response;
use Application\Service\ServiceAdapter;
use Application\Util\Traits\SingleTrait,
    Application\Util\Traits\GetterTrait;
use Application\Database\Database;

final class Application
{
    use SingleTrait;
    use GetterTrait;

    const ENV_DEV = 'dev',
          ENV_STAGE = 'stage',
          ENV_PRODUCTION  = 'production';

    private $env;
    private $config;
    private $service;
    private $request, $response;
    private $db;

    final private function __construct() {
        // set app as global
        set_global('app', $this);

        $this->setConfig(include('./sys/global/cfg.php'));

        // load sys helpers
        foreach (glob('./sys/library/function/*.php') as $file) {
            require_once($file);
        }

        // load app globals
        if (is_file($file = './app/global/def.php')) {
            require_once($file);
        }
        if (is_file($file = './app/global/fun.php')) {
            require_once($file);
        }

        $this->request = new Request();
        $this->db = new Database();
    }

    final public function run() {
        if (!$this->config) {
            throw new \RuntimeException('Call setConfig() first to get run application!');
        }

        $this->setDefaults();

        $this->haltCheck();

        $serviceAdapter = new ServiceAdapter($this);
        if (!$serviceAdapter->isServiceExists()) {
            throw new \Exception(sprintf(
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

    final public function setEnv($env) {
        $this->env = $env;
        return $this;
    }

    final public function setConfig(array $config) {
        if ($this->config == null) {
            $this->config = new Config($config);
        } else {
            $this->config->setData(
                Config::merge($config, $this->config->getData()));
        }
        return $this;
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

    final public function isDev() {
        return $this->env = self::ENV_DEV;
    }
    final public function isStage() {
        return $this->env = self::ENV_STAGE;
    }
    final public function isProduction() {
        return $this->env = self::ENV_PRODUCTION;
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

    final private function haltCheck() {
        // check request count
        $maxRequest = $this->config->get('security.maxRequest');
        if ($maxRequest && count($_REQUEST) > $maxRequest) {
            $this->halt('429 Too Many Requests');
        }
        // check user agent
        $allowEmptyUserAgent = $this->config->get('security.allowEmptyUserAgent');
        if ($allowEmptyUserAgent === false && (
            !isset($_SERVER['HTTP_USER_AGENT']) || !trim($_SERVER['HTTP_USER_AGENT']))) {
            $this->halt('400 Bad Request');
        }
        // check client host
        $hosts = $this->config->get('app.hosts');
        if (!empty($hosts) && (
            !isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $hosts))) {
            $this->halt('400 Bad Request');
        }
        // check file extension
        $allowFileExtensionSniff = $this->config->get('security.allowFileExtensionSniff');
        if ($allowFileExtensionSniff === false &&
            preg_match('~\.(p[hyl]p?|rb|cgi|cf[mc]|p(pl|lx|erl)|aspx?)$~i',
                parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
            $this->halt('400 Bad Request');
        }
        // check service load
        if (sys_getloadavg()[0] > $this->config->get('app.loadAvg')) {
            $this->halt('503 Service Unavailable');
        }
    }
}
