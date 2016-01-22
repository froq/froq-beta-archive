<?php
use Application\Service\Protocol\Site as Service;

class BookDetailService extends Service
{
   protected $useMainOnly = true;

   public function main()
   {
      printf("Book ID: %d\n", $this->app->request->uri->segment(1));
   }
}
