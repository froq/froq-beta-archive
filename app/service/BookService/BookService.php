<?php defined('root') or die('Access denied!');

use \Application\Http\Request;
use \Application\Service\Service;

class BookService extends Service
{
    public function __init__() {
        $this->setRequestMethods(Request::METHOD_GET, Request::METHOD_POST);
        pre($this->app);
    }

    public function __home__() {}

    public function get() {}
    public function post() {}
}

