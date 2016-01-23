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
   public function main() {}

   // GET /book/123
   public function get()
   {
      $this->model->id = (int) $this->app->request->uri->segment(1);
      if (!is_id($this->model->id)) {
         $this->app->response->setStatus(Status::BAD_REQUEST);
         return;
      }

      $book = $this->model->find();
      if (empty($book)) {
         $this->app->response->setStatus(Status::NOT_FOUND);
         return;
      }

      return $book;
   }

   // POST /book
   public function post()
   {
      $this->model->name = trim($this->app->request->params->post['name']);
      $this->model->price = trim($this->app->request->params->post['price']);
      if ($this->model->name == '' || $this->model->price == '') {
         $this->app->response->setStatus(Status::BAD_REQUEST);
         return;
      }

      $id = $this->model->save();
      if (is_id($id)) {
         return ['ok' => true, 'id' => $id];
      }

      return ['ok' => false, 'id' => null];
   }

   // PATCH /book/123
   public function patch()
   {
      $this->model->id = (int) $this->app->request->uri->segment(1);
      $this->model->name = trim($this->app->request->params->post['name']);
      $this->model->price = trim($this->app->request->params->post['price']);
      if (!is_id($this->model->id) || $this->model->name == '' || $this->model->price == '') {
         $this->app->response->setStatus(Status::BAD_REQUEST);
         return;
      }
      if (!$this->model->find()) {
         $this->app->response->setStatus(Status::NOT_FOUND);
         return;
      }

      $result = $this->model->save();
      if (is_int($result)) {
         return ['ok' => true, 'id' => $this->model->id];
      }

      return ['ok' => false, 'id' => null];
   }

   // nope!
   public function put() {}
   public function delete() {}
}
