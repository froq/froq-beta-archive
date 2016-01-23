<?php declare(strict_types=1);
namespace Application\Http\Request;

use Application\Util\Traits\GetterTrait as Getter;
use Application\Http\Request\Params\{Get, Post, Cookie};

/**
 * @package    Application
 * @subpackage Application\Http\Request
 * @object     Application\Http\Request\Params
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Params
{
   /**
    * Getter.
    * @object Application\Util\Traits\GetterTrait
    */
   use Getter;

   /**
    * Get,Post,Cookie params.
    * @var Application\Http\Request\Params\Get,
    *      Application\Http\Request\Params\Post,
    *      Application\Http\Request\Params\Cookie
    */
   private $get, $post, $cookie;

   /**
    * Constructor.
    */
   final public function __construct()
   {
      $this->get = new Get();
      $this->post = new Post();
      $this->cookie = new Cookie();
   }

   /**
    * Get a GET param.
    *
    * @param  string $key
    * @param  mixed  $valueDefault
    * @return mixed
    */
   final public function get(string $key, $valueDefault = null)
   {
      return $this->get->get($key, $valueDefault);
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
      return $this->post->get($key, $valueDefault);
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
      return $this->cookie->get($key, $valueDefault);
   }
}
