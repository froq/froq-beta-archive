<?php namespace Application\Http;

use \Application\Http\Uri\Uri;

final class Request
{
    const METHOD_GET    = 'GET',
          METHOD_POST   = 'POST',
          METHOD_UPDATE = 'UPDATE',
          METHOD_DELETE = 'DELETE';

    private $method;
    private $uri;
    private $params;

    final public function __construct() {
        // set method
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);

        // set uri
        $this->uri = new Uri(
            'http'. (($_SERVER['SERVER_PORT'] == '443') ? 's' : '')
                .'://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
    }
}
