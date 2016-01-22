<?php
use Application\Service\Protocol\Site as Service;

class FailService extends Service
{
   public function main()
   {
      if ($this->viewData['fail']['code'] == 404) {
         $data['error'] = '404 Not Found';
         $data['error_detail'] = $this->viewData['fail']['text'];
         $this->view('./app/service/view/fail/404', $data);
      }
      // @todo add more if's
   }
}
