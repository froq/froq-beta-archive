<?php defined('root') or die('Access denied!');

use \Application\Service\Service;

class HomeService extends Service
{
    public function _main(): string {
        return 'Hello, world!';
    }
}
