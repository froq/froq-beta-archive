<?php defined('root') or die('Access denied!');

use Application\Service\Service;

class HomeService extends Service
{
    public function init() {
        // if (user logged in) {
        //     redirect /home
        // } else {
        //     redirect /account/login
        // }
    }

    public function main(): string {
        return 'Hello, world!';
    }

    public function doFoo() {
        pre(__method__);
    }
}
