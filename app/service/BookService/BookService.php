<?php
use Application\Http\Response\Status;
use Application\Http\Response\ContentType;
use Application\Service\Protocol\Rest as Service;

/**
 * Demo service.
 */
class BookService extends Service
{
   // allowed request methods
   protected $allowedRequestMethods = ['GET', 'POST', 'PATCH'];

   // initialization
   public function init()
   {
      // init model
      $this->model = new BookModel();
      // set default content type
      $this->app->response->setContentType(ContentType::JSON);
   }

   // main method always called
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

   // GET /book/123
   public function get()
   {
      return $this->main();
   }

   // POST /book
   public function post()
   {
      return $this->main();
   }

   // PATCH /book/123
   public function patch()
   {
      return $this->main();
   }

   // nope!
   public function put() {}
   public function delete() {}
}
