<?php
use Application\Service\Service;

class BookService extends Service
{
    protected $useMainOnly = true;
    protected $useViewPartialAll = true;
    protected $allowedRequestMethods = ['GET', 'POST'];

    public function _init() {
        $this->model = new BookModel();
    }

    public function _main() {
        $this->view('main');
        // $this->view->display('main');
    }
}
