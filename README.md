## Froq! Hassle-free service generator.

Froq! is a uber-easy service generator that designed especially for RESTful back-end'd structures, but may be used for front-end'd platforms as well. You simply create your service object (aka resource/endpoint class) and return its actions data.

Let's dive into that sweet thing quickly keeping short the introduction..

### Using as REST Resource Responder

```php
use Application\Http\Response\Status;
use Application\Http\Response\ContentType;
use Application\Service\Protocol\Rest as Service;

class BookService extends Service
{
   // opt: restrict request methods, accept only GET, POST, PATCH
   protected $allowedRequestMethods = ['GET', 'POST', 'PATCH'];

   // @optional initialization
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

Example `GET` call:

```
curl -i -XGET froq.local/book/1
HTTP/1.1 200 OK
Status: 200 OK
Server: Apache/2.4.18 (Ubuntu)
Date: Sat, 23 Jan 2016 15:37:59 GMT
Content-Type: application/json; charset=utf-8
Content-Length: 48
X-Load-Time: 0.0209240913

{"id":1,"name":"PHP in Action","price":16.55}
```

### Using as Site Page Responder

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

## Configuration

All config object's option may be reachable via `config->get()` method or simply array access like `config['key']`. Also its possible request a value by dot notations like `config['x.y.z']`.

Global application config file is in `sys/global/cfg.php` file but user may override all these config options filling `app/global/cfg.php` file. However each service may have its own config file such `FooService/config/config.php`, but these config options reachable only in service object, e.g `$x = $this->config->get('x')` or `$this->config['x']`.

## Services

All service objects must be in `app/service/` in its folder: e.g. `FooService/FooService.php` with same name and suffixed with `Service` phrase, also;

- Each service may have its config file: e.g. `FooService/config/config.php`.
- Each service may have its model file: e.g. `FooService/model/model.php`.
- Each service may have its view file: e.g. `FooService/view/view.php`.

A service `init()` method always called first of all methods, so it could be used as constructor. By the way, you can not define `__construct()` method in any service cos it's finalized in parent object.

A service `main()` method could be handler for all requests that invoked service, just set `$useMainOnly = true` for this action.

A service could have `onBefore()` and `onAfter()` methods to simply implement event-driven processes but not async.

Following methods must be implemented by child object by service type;

- For `Site` services: `main()`.<br>
Note: All other methods must be prefixed with `do` phrase E.g: for `/book/save`, book service must have `doSave()` method.

- For `Rest` services: `main()`, `get()`, `post()`, `put()`, `patch()`, `delete()`.<br>
Note: Even service does not handle all these method must be found in extender service object.


## Partials

All service may have individual `head/foot` file in its own folder such as `FooService/view/partial/head.php`. If it has no partial file(s) then default partial file(s) will be included and used. Services could be directed to use `head/foot` file setting `$useViewPartialAll = true`, or use only `head` setting `$useViewPartialHead = true` or vice versa `foot` setting `$useViewPartialFoot = true` in service object.

## Output Handling

Output handler is optional but it could be useful sometimes for a developer. For example, while working with single header file, it could be headache to set page title differently for each page, or using different image/resource files for different pages. Using output handler, that could be done as well. Remember, when it is called the output did not `gzip'd` yet.

Output handler could be defined for once and in `pub/index.php` (see line 36).

```php
// simply fulfill callback closure
$app->setHandler('output', function($output) {
   // and do something with output
   return str_replace("\t", "   ", $output);
});
```

## Service Environments

If you set your local development server like `froq.local`, Froq! will decide that you are on local development environment. Otherwise, it always thinks running on production environment. Note: this feature is not implemented yet properly/completely, just all exceptions thrown directly if environment is local.

## Service Roots (Base URI)

Roots could be set easily in `pub/index.php`. For example, if you want to use a versioning approach in your applications, you can set `$appRoot = '/api/v1'`. Then call your URL's like `/api/v1/book/123` but just define your book service as `BookService` normally (so you do not need to create another service such as `ApiService` for all these requests cos `/api/v1` part will be dropped).

## Fails (Error Handling)

All fails go to `app/service/default/FailService` file, so you can easily default fail files as wish.

## Composer Support

You can integrate any library you want use into Froq!. Composer's `vendor` file will be in root (`/`) directory and composer's autoloader will be included automatically in application.


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

## Todo

- Wiki.
- Add callback-driven service definition support.
- Have a tequila.

## Logo

Huge thanks: http://www.clipartbest.com/clipart-LcKqXnbca
