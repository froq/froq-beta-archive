<?php defined('root') or die('Access denied!');

use Application\Service\Service;

class __FailService extends Service
{
    protected $useMainOnly = true;

    public function main() {
        if ($this->viewData['fail']['code'] == 404) {
            $data['error'] = '404 Not Found';
            $data['error_detail'] = $this->viewData['fail']['text'];
            $this->view('./app/service/view/fail/404', $data);
        }
    }
}
