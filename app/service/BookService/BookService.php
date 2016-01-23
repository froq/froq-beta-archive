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
      pre($this->method);
   }

   public function get()
   {
      $this->main();
   }

   public function post()
   {
      print __method__;
   }

   public function put()
   {
      print __method__;
   }

   public function patch()
   {
      print __method__;
   }

   public function delete()
   {
      print __method__;
   }
}
