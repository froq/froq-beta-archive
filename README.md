## Froq! Simply a service generator..

Froq! is a uber-easy service generator that designed especially for RESTful back-end'd structures, but may be used for front-end'd platforms as well. You simply create your service object (aka resource/endpoint class) and return its actions data.

## In a Nutshell

Using as site page responder.

```php
use Application\Service\Protocol\Site as Service;

class BookService extends Service
{
   // opt: redirect all requests to main()
   protected $useMainOnly = true;
   // opt: use header/footer partials
   protected $useViewPartialAll = true;
   // opt: restrict requests method, accept only GET, POST
   protected $allowedRequestMethods = ['GET', 'POST'];

   // @optional initialization
   public function init()
   {
      // init model
      $this->model = new BookModel();
   }

   // @required main method
   public function main()
   {
      $id = (int) $this->app->request->uri->segment(1);
      // find book by id
      $book = $this->model->find($id);
      // show it in view as you wish
      $this->view('main', $book);
   }
}
```

Using as REST resource responder.

```php
<?php
use Application\Http\Response\Status;
use Application\Http\Response\ContentType;
use Application\Service\Protocol\Rest as Service;

class BookService extends Service
{
   // opt: restrict requests method, accept only GET, POST
   protected $allowedRequestMethods = ['GET', 'POST', 'PATCH', 'DELETE'];

   // initialization
   public function init()
   {
      // init model
      $this->model = new BookModel();
      // set default content type
      $this->app->response->setContentType(ContentType::JSON);
   }

   // @required main method
   public function main()
   {}

   // @required get method GET /book/123
   public function get()
   {
      $id = (int) $this->app->request->uri->segment(1);
      // check: "$id > 0"
      if (!is_id($id)) {
         $this->app->response->setStatus(Status::BAD_REQUEST);
         $this->app->response->setContentType(ContentType::NONE);
         return null;
      }

      // find book by id
      $book = $this->model->find($id);
      if (empty($book)) {
         return null;
      }

      // return book object that will be json encoded
      return $book;
   }

   // @required all
   // public function post() {}
   // public function put() {}
   // public function patch() {}
   // public function delete() {}
}
```
