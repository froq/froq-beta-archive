<?php declare(strict_types=1);
namespace Application\Http;

use Application\Http\Response\Status;
use Application\Util\Traits\GetterTrait as Getter;
use Application\{Encoding\Gzip /*, @todo Encoding\Xml, Encoding\Json */ };

/**
 * @package    Application
 * @subpackage Application\Http
 * @object     Application\Http\Response
 * @uses       Application\Encoding\Gzip,
 *             @todo Application\Encoding\Xml,
 *             @todo Application\Encoding\Json,
 *             Application\Http\Response\Status,
 *             Application\Util\Traits\SetGetTrait
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Response
{
   /**
    * Getter.
    * @object Application\Util\Traits\GetterTrait
    */
   use Getter;

   /**
    * Content types.
    * @const string
    */
   const CONTENT_TYPE_NA   = 'n/a',
         CONTENT_TYPE_HTML = 'text/html',
         CONTENT_TYPE_XML  = 'application/xml',
         CONTENT_TYPE_JSON = 'application/json';

   /**
    * Content charset.
    * @const string
    */
   const CONTENT_CHARSET = 'utf-8';

   /**
    * HTTP Version.
    * @var string
    */
   private $httpVersion;

   /**
    * Content type, encoding, length.
    * @var string, string, int
    */
   private $contentType    = self::CONTENT_TYPE_HTML,
           $contentCharset = self::CONTENT_CHARSET,
           $contentLength  = 0;

   /**
    * Gzip.
    * @var Application\Encoding\Gzip
    */
   private $gzip;

   /**
    * Gzip options.
    * @var array
    */
   private $gzipOptions = [];

   /**
    * Status object.
    * @var Application\Http\Response\Status
    */
   private $status;

   /**
    * Body stack.
    * @var string
    */
   private $body = '';

   /**
    * Headers object.
    * @varApplication\Http\Headers
    */
   private $headers;

   /**
    * Cookies object.
    * @varApplication\Http\Cookies
    */
   private $cookies;

   /**
    * Constructor.
    *
    * @param string $body
    * @param array  $status (eg: [code] or [code, text])
    * @param array  $headers
    * @param array  $cookies
    */
   final public function __construct(array $status = null, string $body = '',
      array $headers = [], array $cookies = [])
   {
      // set http version
      $this->httpVersion = $_SERVER['SERVER_PROTOCOL'];

      // set status if provided
      if (empty($status)) {
         $this->status = new Status(Status::DEFAULT_CODE, Status::DEFAULT_TEXT);
      } else {
         @list($statusCode, $statusText) = $status;
         if (empty($statusText)) {
            $statusText = Status::getStatusText($statusCode);
         }
         $this->status = new Status($statusCode, $statusText);
      }

      // set body if provided
      if ($body) {
         $this->setBody($body);
      }

      // gzip
      $this->gzip = new Gzip();

      // set headers/cookies as an object that iterable/traversable
      $this->headers = new Headers($headers);
      $this->cookies = new Cookies($cookies);
   }

   /**
    * Redirect client intantly to the given location.
    *
    * Notice: This methods causes the program to quit completely.
    * Also you need to escape the target location, it won't do it
    * at all.
    *
    * @param  string   $location
    * @param  int      $code
    * @return void
    */
   final public function redirect(string $location, int $code = 302)
   {
      $this->setStatus($code);
      $this->setHeader('Location', $location);
   }

   /**
    * Set Gzip config.
    *
    * @param  array $gzipOptions
    * @return self
    */
   final public function setGzipOptions(array $gzipOptions): self
   {
      if (isset($gzipOptions['level'])) {
         $this->gzip->setLevel($gzipOptions['level']);
      }
      if (isset($gzipOptions['mode'])) {
         $this->gzip->setMode($gzipOptions['mode']);
      }
      if (isset($gzipOptions['minlen'])) {
         $this->gzip->setDataMinLength($gzipOptions['minlen']);
      }

      $this->gzipOptions = $gzipOptions;

      return $this;
   }

   /**
    * Set status.
    *
    * @param  int    $code
    * @param  string $text
    * @return self
    */
   final public function setStatus(int $code, string $text = null): self
   {
      if ($text == null) {
         $text = Status::getStatusText($code);
      }

      $this->status->code = $code;
      $this->status->text = $text;

      return $this;
    }

   /**
    * Set status code.
    *
    * @param  int $code
    * @return self
    */
   final public function setStatusCode(int $code): self
   {
      $this->status->code = $code;

      return $this;
   }

   /**
    * Set status text.
    *
    * @param  string $text
    * @return self
    */
   final public function setStatusText(string $text): self
   {
      $this->status->text = $text;

      return $this;
   }

   /**
    * Set content type.
    *
    * @param  string $contentType
    * @return self
    */
   final public function setContentType(string $contentType): self
   {
      $this->contentType = trim($contentType);

      return $this;
   }

   /**
    * Set content charset.
    *
    * @param  string $contentCharset
    * @return self
    */
   final public function setContentCharset(string $contentCharset): self
   {
      $this->contentCharset = trim($contentCharset);

      return $this;
   }

   /**
    * Set content length.
    *
    * @param  int $contentLength
    * @return self
    */
   final public function setContentLength(int $contentLength): self
   {
      $this->contentLength = $contentLength;

      return $this;
   }

   /**
    * Set a header.
    *
    * Notice: All these stored headers should be sent before
    * sending the last output to the client with self.send()
    * method.
    *
    * @param  string $name
    * @param  mixed  $value
    * @return self
    */
   final public function setHeader(string $name, $value): self
   {
      $this->headers->set($name, $value);

      return $this;
   }

   /**
    * Send a header instantly.
    *
    * @param  string $name
    * @param  mixed  $value
    * @return void
    */
   final public function sendHeader(string $name, $value)
   {
      // headers sent?
      if (headers_sent()) return;

      // to remove a header set value as null
      if ($value === null) {
         return $this->removeHeader($name);
      }

      // send
      header(sprintf('%s: %s', $name, trim((string) $value)));
    }

   /**
    * Send all stored response headers.
    *
    * @return void
    */
   final public function sendHeaderAll()
   {
      if ($this->headers->count()) {
         foreach ($this->headers as $name => $value) {
            $this->sendHeader($name, $value);
         }
      }
   }

   /**
    * Remove a header (instantly).
    *
    * @param  string $name
    * @param  bool   $defer
    * @return void
    */
   final public function removeHeader(string $name, bool $defer = false)
   {
      unset($this->headers[$name]);

      // remove instantly?
      if (!$defer) {
         header_remove($name);
      }
   }

   /**
    * Remove all headers.
    *
    * @return void
    */
   final public function removeHeaderAll()
   {
      if ($this->headers->count()) {
         foreach ($this->headers as $name => $dummy) {
            $this->removeHeader($name);
         }
      }
   }

   /**
    * Set a response cookie.
    *
    * Notice: All these stored cookies should be sent before
    * sending the last output to the client with self.send()
    * method.
    *
    * @param  string $name
    * @param  any    $value
    * @param  int    $expire
    * @param  string $path
    * @param  string $domain
    * @param  bool   $secure
    * @param  bool   $httponly
    * @throws \InvalidArgumentException
    * @return void
    */
   final public function setCookie(string $name, $value, int $expire = 0,
      string $path = '/', string $domain = null, bool $secure = false, bool $httponly = false)
   {
      // check name
      if (!preg_match('~^[a-z0-9_\-]+$~i', $name)) {
         throw new \InvalidArgumentException('Cookie name not accepted!');
      }

      // store cookie
      $this->cookies->set($name, [
         'name'     => $name,    'value'  => $value,
         'expire'   => $expire,  'path'   => $path,
         'domain'   => $domain,  'secure' => $secure,
         'httponly' => $httponly
      ]);
   }

   /**
    * Send a cookie instantly.
    *
    * @param  string $name
    * @param  any    $value
    * @param  int    $expire
    * @param  string $path
    * @param  string $domain
    * @param  bool   $secure
    * @param  bool   $httponly
    * @throws \InvalidArgumentException
    * @return bool
    */
   final public function sendCookie(string $name, $value, int $expire = 0,
      string $path = '/', string $domain = null, bool $secure = false, bool $httponly = false): bool
   {
      // check name
      if (!preg_match('~^[a-z0-9_\-]+$~i', $name)) {
         throw new \InvalidArgumentException('Cookie name not accepted!');
      }

      // send cookie
      return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
   }

   /**
    * Send all stored cookies.
    *
    * @return void
    */
   final public function sendCookieAll() {
      if ($this->cookies->count()) {
         foreach ($this->cookies as $cookie) {
            $this->sendCookie($cookie['name'], $cookie['value'], $cookie['expire'],
               $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
         }
      }
   }

   /**
    * Remove a cookie (instantly).
    *
    * @param  string $name
    * @param  bool   $defer
    * @return void
    */
   final public function removeCookie(string $name, bool $defer = false)
   {
      unset($this->cookies[$name]);

      // remove instantly?
      if (!$defer) {
         $this->sendCookie($name, null, 322869600);
      }
   }

   /**
    * Remove all cookies.
    *
    * @return void
    */
   final public function removeCookieAll()
   {
      if ($this->cookies->count()) {
         foreach ($this->cookies as $name => $dummy) {
            $this->removeCookie($name);
         }
      }
   }

   /**
    * Set body.
    *
    * @param  mixed $body
    * @return self
    */
   final public function setBody($body): self
   {
      // @todo
      // check content type
      // if ($this->contentType == self::CONTENT_TYPE_HTML) {
      //    // check for page title
      //       if ($pageTitle = get_global('page.title')) {
      //          $body = preg_replace(
      //             '~<title>(.*?)</title>~s',
      //              '<title>'. html_encode($pageTitle) .'</title>',
      //                $body, 1 // only once
      //          );
      //       }
      //       // check page description
      //       if ($pageDescription = get_global('page.description')) {
      //          $body = preg_replace(
      //             '~<meta\s+name="description"\s+content="(.*?)">~',
      //              '<meta\s+name="description"\s+content="'. html_encode($pageDescription) .'">',
      //                $body, 1 // only once
      //          );
      //       }
      // }
      // @todo
      // elseif ($this->contentType == self::CONTENT_TYPE_XML) {
      // } elseif ($this->contentType == self::CONTENT_TYPE_JSON) {
      // }

      // can gzip?
      if (!empty($this->gzipOptions)) {
         $body = $this->gzip->encode($body);
         $this->setHeader('Vary', 'Accept-Encoding');
         $this->setHeader('Content-Encoding', 'gzip');
      }

      // content length
      $this->setContentLength(strlen($body));

      $this->body = $body ."\n";

      return $this;
   }

   /**
    * Send status, content type and body.
    *
    * @return void
    */
   final public function send()
   {
      // send status
      header(sprintf('%s %s',
         $this->httpVersion, $this->status->code, $this->status->text));
      // send status (just for fun)
      header(sprintf('Status: %s %s',
         $this->status->code, $this->status->text));

      // content type / length
      $this->sendHeader('Content-Length', $this->contentLength);
      if (empty($this->contentType)) {
         $this->sendHeader('Content-Type', self::CONTENT_TYPE_NA);
      } elseif (empty($this->contentCharset)
         || strtolower($this->contentType) == self::CONTENT_TYPE_NA) {
            $this->sendHeader('Content-Type', $this->contentType);
      } else {
         $this->sendHeader('Content-Type',
            sprintf('%s; charset=%s', $this->contentType, $this->contentCharset));
      }

      // real load time
      // $this->sendHeader('X-Load-Time', app()->loadTime());

      // print it beybe!
      print $this->body;
   }

   // @wait
   final public function sendFile($file) {}
}
