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
      $this->model->id = 1; // get from request
      $book = $this->model->find();
      pre($book);

      $books = $this->model->findAll();
      $books = $this->model->findAll('id > ?', [0]);
      pre($books);

      // $this->view('main');
      // $this->view->display('main');
   }
}
