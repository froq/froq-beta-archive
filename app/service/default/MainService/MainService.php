<?php
use Application\Service\Protocol\Rest as Service;

class MainService extends Service
{
   public function main()
   {
      // $this->app->response->setStatus(400);
      print '400 Bad Request';
   }

   public function get() {}
   public function post() {}
   public function put() {}
   public function patch() {}
   public function delete() {}
}
