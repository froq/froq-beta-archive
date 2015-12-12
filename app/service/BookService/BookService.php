<?php defined('root') or die('Access denied!');

use Application\Http\Request;
use Application\Service\Service;

class BookService extends Service
{
    protected $useMainOnly = true;
    protected $allowedRequestMethods = [Request::METHOD_GET, Request::METHOD_POST];

    public function _init() {
        $this->model = new BookModel();
    }

    public function _main() {
        $this->view('main');
        // $this->view->display('main');
    }
}
