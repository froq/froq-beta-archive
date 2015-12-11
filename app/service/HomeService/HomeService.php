<?php defined('root') or die('Access denied!');

use \Application\Http\Request;
use \Application\Service\Service;

class HomeService extends Service
{
    public function _home() {
        return 'Hello, world!';
    }
}
