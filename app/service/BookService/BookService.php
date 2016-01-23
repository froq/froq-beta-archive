<?php
use Application\Service\Protocol\Rest as Service;

class BookService extends Service
{
   protected $allowedRequestMethods = ['GET', 'POST', 'PATCH', 'DELETE'];

   public function init()
   {
      // init model
      $this->model = new BookModel();

      // set default content type
      $this->app->response->setContentType('application/json');
   }

   public function main()
   {
      return ['hello' => 'world!'];
   }

   public function get()
   {
      return $this->main();
   }

   public function post()
   {
      return $this->main();
   }

   public function put()
   {
      return $this->main();
   }

   public function patch()
   {
      return $this->main();
   }

   public function delete()
   {
      return $this->main();
   }
}
