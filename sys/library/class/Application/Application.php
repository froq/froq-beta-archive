<?php declare(strict_types=1);
namespace Application;

use Application\Util\{Config, Session};
use Application\Util\Traits\{SingleTrait as Single, GetterTrait as Getter};
use Application\Http\{Request, Response};
use Application\Service\{ServiceAdapter, ServiceInterface};
use Application\Database\Database;
use Application\Handler\{Error as ErrorHandler, Exception as ExceptionHandler, Shutdown as ShutdownHandler};

final class Application
{
   use Single;
   use Getter;

   const ENVIRONMENT_DEVELOPMENT = 'development',
         ENVIRONMENT_STAGE       = 'stage',
         ENVIRONMENT_PRODUCTION  = 'production';

   private $env;
   private $root = '/'; // @todo
   private $service;
   private $session;
   private $request, $response;
   private $config;

   final private function __construct()
   {
      // set app as global
      set_global('app', $this);

      // set default config first
      $this->setConfig(include('./sys/global/cfg.php'));

      // load sys helpers
      $files = glob('./sys/library/function/*.php');
      foreach ($files as $file) {
         require_once($file);
      }

      // load app globals
      if (is_file($file = './app/global/def.php')) {
         require_once($file);
      }
      if (is_file($file = './app/global/fun.php')) {
         require_once($file);
      }

      // composer
      $autoload = './vendor/autoload.php';
      if (is_file($autoload)) {
         $autoload = require($autoload);
         $autoload->register();
      }

      // set handlers
      $this->setErrorHandler();
      $this->setExceptionHandler();
      $this->setShutdownHandler();

      $this->request = new Request();
      $this->response = new Response();
   }

   /**
    * Restore defaults.
    *
    * @return void
    */
   final public function __destruct()
   {
      restore_include_path();
      restore_error_handler();
      restore_exception_handler();
   }

   /**
    * Set error handler.
    *
    * @return mixed
    */
   final public function setErrorHandler()
   {
      return set_error_handler(ErrorHandler::handler());
   }

   /**
    * Set exception handler.
    *
    * @return mixed
    */
   final public function setExceptionHandler()
   {
      return set_exception_handler(ExceptionHandler::handler());
   }

   /**
    * Set shutdown handler.
    *
    * @return void
    */
   final public function setShutdownHandler()
   {
      register_shutdown_function(ShutdownHandler::handler());
   }

   final public function run()
   {
      if (!$this->config) {
         throw new \RuntimeException('Call setConfig() first to get run application!');
      }

      $this->setDefaults();

      if ($halt = $this->haltCheck()) {
         $this->halt($halt);
      }

      $this->startOutputBuffer();

      $this->service = (new ServiceAdapter($this))
         ->getService();

      if ($this->service->protocol == ServiceInterface::PROTOCOL_SITE) {
         $this->session = Session::init($this->config['app.session.cookie']);
      }

      $output = $this->service->run();

      $this->endOutputBuffer($output);
   }

   final public function setEnv(string $env): self
   {
      $this->env = $env;
      return $this;
   }

   final public function setConfig(array $config): self
   {
      if ($this->config == null) {
        $this->config = new Config($config);
      } else {
         $this->config->setData(Config::merge($config, $this->config->getData()));
      }
      return $this;
   }

   final public function setDefaults(): self
   {
      $cfg = ['locale'   => $this->config['app.locale'],
              'encoding' => $this->config['app.encoding'],
              'timezone' => $this->config['app.timezone'],
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

   final public function startOutputBuffer()
   {
      ini_set('implicit_flush', '1');

      $gzipOptions = $this->config->get('app.gzipz', []);
      if (!empty($gzipOptions)) {
         if (!headers_sent()) {
            ini_set('zlib.output_compression', '0');
         }
         $this->response->setGzipOptions($gzipOptions);
      }
      ob_start();
   }

   final public function endOutputBuffer(string $output = null)
   {
      // print'ed service methods return null
      if ($output === null) {
         $output = '';
         while (ob_get_level()) {
            $output .= ob_get_clean();
         }
      }

      $outputFilter = get_global('app.handler.output');
      if (is_callable($outputFilter)) {
         $output = $outputFilter($output);
      }

      print $output;
      print PHP_EOL;
   }

   final public function isDev(): bool
   {
      return ($this->env == self::ENVIRONMENT_DEVELOPMENT);
   }
   final public function isStage(): bool
   {
      return ($this->env == self::ENVIRONMENT_STAGE);
   }
   final public function isProduction(): bool
   {
      return ($this->env == self::ENVIRONMENT_PRODUCTION);
   }

   final private function halt(string $status)
   {
      header(sprintf('%s %s', $_SERVER['SERVER_PROTOCOL'], $status));
      header('Connection: close');
      header('Content-Type: none');
      header('Content-Length: 0');
      header_remove('X-Powered-By');
      exit(1);
   }

   final private function haltCheck(): string
   {
      // check request count
      $maxRequest = $this->config->get('security.maxRequest');
      if ($maxRequest && count($_REQUEST) > $maxRequest) {
         return '429 Too Many Requests';
      }

      // check user agent
      $allowEmptyUserAgent = $this->config->get('security.allowEmptyUserAgent');
      if ($allowEmptyUserAgent === false
         && (!isset($_SERVER['HTTP_USER_AGENT']) || !trim($_SERVER['HTTP_USER_AGENT']))) {
         return '400 Bad Request';
      }

      // check client host
      $hosts = $this->config->get('app.hosts');
      if (!empty($hosts)
         && (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $hosts))) {
         return '400 Bad Request';
      }

      // check file extension
      $allowFileExtensionSniff = $this->config->get('security.allowFileExtensionSniff');
      if ($allowFileExtensionSniff === false
         && preg_match('~\.(p[hyl]p?|rb|cgi|cf[mc]|p(pl|lx|erl)|aspx?)$~i',
               parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
         return '400 Bad Request';
      }

      // check service load
      if (sys_getloadavg()[0] > $this->config->get('app.loadAvg')) {
         return '503 Service Unavailable';
      }

      return '';
   }
}
