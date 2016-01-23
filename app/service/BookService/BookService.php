<?php
use Application\Http\Response\Status;
use Application\Http\Response\ContentType;
use Application\Service\Protocol\Rest as Service;

class BookService extends Service
{
   protected $allowedRequestMethods = ['GET', 'POST', 'PATCH', 'DELETE'];

   public function init()
   {
      // init model
      $this->model = new BookModel();
      // set default content type
      $this->app->response->setContentType(ContentType::JSON);
   }

   public function main()
   {
      $id = (int) $this->app->request->uri->segment(1);
      if (!is_id($id)) {
         $this->app->response->setStatus(Status::BAD_REQUEST);
         $this->app->response->setContentType(ContentType::NONE);
         return null;
      }

      $book = $this->model->find($id);
      if (empty($book)) {
         return null;
      }

      return $book;
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
   {}

   public function patch()
   {
      return $this->main();
   }

   public function delete()
   {
      return $this->main();
   }
}
