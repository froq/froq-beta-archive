<?php
namespace Application\Http\Request;

/**
 * @package    Application
 * @subpackage Application\Http\Request
 * @object     Application\Http\Request\Params
 * @author     Kerem! <qeremy@gmail>
 */
final class Params
{
    /**
     * Object constructor.
     */
    // final public function __construct() {}

    /**
     * Get a GET param.
     *
     * @param  string $key
     * @param  mixed  $valueDefault
     * @return mixed
     */
    final public function get($key, $valueDefault = null) {
        return dig($_GET, $key, $valueDefault);
    }

    /**
     * Get a POST param.
     *
     * @param  string $key
     * @param  mixed  $valueDefault
     * @return mixed
     */
    final public function post($key, $valueDefault = null) {
        return dig($_POST, $key, $valueDefault);
    }

    /**
     * Get a COOKIE param.
     *
     * @param  string $key
     * @param  mixed  $valueDefault
     * @return mixed
     */
    final public function cookie($key, $valueDefault = null) {
        return dig($_COOKIE, $key, $valueDefault);
    }
}
