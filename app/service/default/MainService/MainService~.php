<?php
use Application\Service\Protocol\Site as Service;

class MainService extends Service
{
   public function init()
   {}

   public function main()
   {
      return 'Hello, Froq!';
   }

   public function doFoo()
   {
      $this->main();
   }
}
