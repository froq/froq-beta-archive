<?php defined('root') or die('Access denied!');

use Application\Http\Request;
use Application\Service\Service;

class BookService extends Service
{
    protected $mainOnly = true;
    protected $allowedRequestMethods = [Request::METHOD_GET, Request::METHOD_POST];

    public function _init() {
        $this->model = new BookModel();
    }

    public function _main() {
        $this->model->id = 1;
        pre($this->model);
    }
}
