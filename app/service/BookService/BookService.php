<?php defined('root') or die('Access denied!');

use \Application\Http\Request;
use \Application\Service\Service;

class BookService extends Service
{
    protected $methodAccept = false;
    protected $allowedRequestMethods = [Request::METHOD_GET, Request::METHOD_POST];

    // public function __init__() {
    //     pre($this->config);
    // }

    public function _main() {
        $this->printId();
    }

    public function __before__() {
        pre(__method__);
    }
    public function __after__() {
        pre(__method__);
    }

    private function printId() {
        $id = $this->app->request->uri->segment(1);
        print $id;
    }
}

