<?php declare(strict_types=1);
namespace Application\Http\Request;

/**
 * @package    Application
 * @subpackage Application\Http\Request
 * @object     Application\Http\Request\Params
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Params
{
   /**
    * Constructor.
    */
   // final public function __construct() {}

   /**
    * Get a GET param.
    *
    * @param  string $key
    * @param  mixed  $valueDefault
    * @return mixed
    */
   final public function get(string $key, $valueDefault = null)
   {
      return dig($_GET, $key, $valueDefault);
   }

   /**
    * Get a POST param.
    *
    * @param  string $key
    * @param  mixed  $valueDefault
    * @return mixed
    */
   final public function post(string $key, $valueDefault = null)
   {
      return dig($_POST, $key, $valueDefault);
   }

   /**
    * Get a COOKIE param.
    *
    * @param  string $key
    * @param  mixed  $valueDefault
    * @return mixed
    */
   final public function cookie(string $key, $valueDefault = null)
   {
      return dig($_COOKIE, $key, $valueDefault);
   }
}
