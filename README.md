## Froq! Simply a service generator..

Froq! is a uber-easy service generator that designed especially for RESTful back-end'd structures, but may be used for front-end'd platforms as well. You simply create your service object (aka resource/endpoint class) and return its actions data.

## In a Nutshell

Using as site page responder.

```php
use Application\Service\Protocol\Site as Service;

class BookService extends Service
{
   // opt: redirect all requests to main(), default=false
   protected $useMainOnly = true;
   // opt: use header/footer partials, default=false
   protected $useViewPartialAll = true;
   // opt: restrict request methods, accept only GET, POST, default=[] so accept all
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

   // when $useMainOnly=false for "/book/foo" calls
   // public function doFoo() { ... }
}
```

Using as REST resource responder.

```php
use Application\Http\Response\Status;
use Application\Http\Response\ContentType;
use Application\Service\Protocol\Rest as Service;

class BookService extends Service
{
   // opt: restrict request methods, accept only GET, POST, PATCH
   protected $allowedRequestMethods = ['GET', 'POST', 'PATCH'];

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

   // @required get method "GET /book/123"
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
   // public function post() { ... }
   // public function put() { ... }
   // public function patch() { ... }
   // public function delete() { ... }
}
```

## Folder Structure

As you guess, all files in `app` directory your and may be modified as you wish, as well as `pub` directory but `index.php`.

```bash
./
   app/ # all your in app folder
      global/
         cfg.php         # user configs
         def.php         # user constants
         fun.php         # user functions (misc.)
      library/
         class/          # user objects (class, trait etc.)
         function/       # user functions (language, localization etc.)
      service/
         BookService/
            config/      # optional (service specific configs)
               config.php
            model/
               model.php # optional, where BookModel comes
            view/
               main.php  # optional, for main() method
               ...
            BookService.php
         default/
            FailService/
               FailService.php
            MainService/
               MainService.php
         view/
            fail/
               main.php
               403.php
               404.php
               ...
            partial/
               head.php
               foot.php

   pub/ # where assets, images go
      index.php
   sys/ # core
      ...
```
