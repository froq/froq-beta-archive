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
      $id = (int) $this->app->request->uri->segment(1);
      if (!is_id($id)) {
         $this->app->response->setStatus(400);
         $this->app->response->setContentType('none');
         return null;
      }

      pre($id);
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
