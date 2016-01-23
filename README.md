## Froq! Simply a service generator..

Froq! is a uber-easy service generator that designed especially for RESTful back-end'd structures, but may be used for front-end'd platforms as well. You simply create your service object (aka resource/endpoint class) and return its actions data.

## In a Nutshell

```php
<?php
// use as front-end responder
class BookService extends Application\Service\Protocol\Site
{
   // cmd: redirect all requests to main()
   protected $useMainOnly = true;
   // cmd: use header/footer partials
   protected $useViewPartialAll = true;
   // cmd: restrict requests method, accept only GET, POST
   protected $allowedRequestMethods = ['GET', 'POST'];

   // initialization
   public function init()
   {
      $this->model = new BookModel();
   }

   // main method
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
