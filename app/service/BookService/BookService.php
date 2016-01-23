<?php
use Application\Service\Protocol\Rest as Service;

class BookService extends Service
{
   protected $allowedRequestMethods = ['GET', 'POST', 'PATCH', 'DELETE'];

   public function init()
   {
      $this->model = new BookModel();
   }

   public function main()
   {
      printf('BookService::%s()', $this->method);
   }

   public function get()
   {
      $this->main();
   }

   public function post()
   {
      $this->main();
   }

   public function put()
   {
      $this->main();
   }

   public function patch()
   {
      $this->main();
   }

   public function delete()
   {
      $this->main();
   }
}
