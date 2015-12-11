<?php defined('root') or die('Access denied!');

use \Application\Http\Request;
use \Application\Service\Service;

class BookService extends Service
{
    protected $methodAccept = false;
    protected $allowedRequestMethods = [Request::METHOD_GET, Request::METHOD_POST];

    // public function __init__() {}

    public function __home__() {
        $this->printId();
    }

    private function printId() {
        // $id = $this->app->request->uri->segment(1);
        $id = $this->uri->segment(1);
        print $id;
    }
}

