<?php
use Application\Service\Protocol\Site as Service;

class BookService extends Service
{
    protected $useMainOnly = true;
    protected $useViewPartialAll = true;
    protected $allowedRequestMethods = ['GET', 'POST'];

    public function init() {
        $this->model = new BookModel();
    }

    public function main() {
        $book = $this->model->find();
        pre($book);
        // $this->view('main');
        // $this->view->display('main');
    }
}
