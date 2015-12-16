<?php defined('root') or die('Access denied!');

use Application\Service\Service;

class HomeService extends Service
{
    public function _init() {
        // if (user logged in) {
        //     redirect /home
        // } else {
        //     redirect /account/login
        // }
    }

    public function _main(): string {
        return 'Hello, world!';
    }
}
