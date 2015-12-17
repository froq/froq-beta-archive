<?php defined('root') or die('Access denied!');

use Application\Service\Service;

class BookDetailService extends Service
{
    protected $useMainOnly = true;

    public function main() {
        printf("Book ID: %d\n", $this->app->request->uri->segment(1));
    }
}
