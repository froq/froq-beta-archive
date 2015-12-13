<?php
use Application\Service\Service;

class BookDetailService extends Service
{
    protected $useMainOnly = true;

    public function _main() {
        printf("Book ID: %d\n", $this->app->request->uri->segment(1));
    }
}
