<?php defined('root') or die('Access denied!');

use Application\Service\Service;

class BookService extends Service
{
    protected $useMainOnly = true;
    protected $useViewPartialAll = true;
    protected $allowedRequestMethods = ['GET', 'POST'];

    public function init() {
        $this->model = new BookModel();
    }

    public function main() {
        $this->view('main');
        // $this->view->display('main');
    }
}
