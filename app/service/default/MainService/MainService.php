<?php defined('root') or die('Access denied!');

use Application\Service\Protocol\Site as Service;

class MainService extends Service
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
}
