<?php defined('root') or die('Access denied!');

use \Application\Http\Request;
use \Application\Service\Service;

class BookService extends Service
{
    protected $mainOnly = true;
    protected $allowedRequestMethods = [Request::METHOD_GET, Request::METHOD_POST];

    // public function _init() {
    //     pre($this->config);
    // }

    public function _main() {
        $this->printId();
    }

    public function _before() {
        pre(__method__);
    }
    public function _after() {
        pre(__method__);
    }

    private function printId() {
        $id = $this->app->request->uri->segment(1);
        print $id;
    }
}

