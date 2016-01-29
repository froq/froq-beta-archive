<?php
use Application\Service\Protocol\Site as Service;

class FailService extends Service
{
   public function main()
   {
      if (!isset($this->viewData['fail']['code'])) {
         return $this->view('./app/service/view/fail/main', $data);
      }

      if ($this->viewData['fail']['code'] == 404) {
         $data['error'] = '404 Not Found';
         $data['error_detail'] = $this->viewData['fail']['text'];
      } elseif ($this->viewData['fail']['code'] == 500) {
         $data['error'] = '500 Internal Server Error';
         $data['error_detail'] = $this->viewData['fail']['text'];
      // } elseif () { ...
      }

      $this->view('./app/service/view/fail/main', $data);
   }
}
