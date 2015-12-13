<?php namespace Application\Http;

use \Application\Http\Uri\Uri;
use \Application\Util\Traits\GetterTrait;

final class Request
{
    use GetterTrait;

    const METHOD_GET    = 'GET',
          METHOD_POST   = 'POST',
          METHOD_UPDATE = 'UPDATE',
          METHOD_DELETE = 'DELETE';

    private $method;
    private $scheme;
    private $uri;
    private $params;

    final public function __construct() {
        // set method
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);

        // set scheme
        if (isset($_SERVER['REQUEST_SCHEME'])) {
            $this->scheme = strtolower($_SERVER['REQUEST_SCHEME']);
        } elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
            $this->scheme = 'https';
        } else {
            $this->scheme = 'http';
        }

        // set uri
        $this->uri = new Uri($this->scheme .'://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
    }
}
