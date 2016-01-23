<?php declare(strict_types=1);
namespace Application\Encoding;

/**
 * @package    Application
 * @subpackage Application\Encoding
 * @object     Application\Encoding\Json
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Json
{
   /**
    * Data hodler.
    * @var mixed
    */
   private $data;

   /**
    * Error code.
    * @var int
    */
   private $errorCode = 0;

   /**
    * Error message.
    * @var string
    */
   private $errorMessage = '';

   /**
    * Error message map.
    * @var array
    */
   private static $errorMessages = [
      JSON_ERROR_NONE           => '',
      JSON_ERROR_DEPTH          => 'Maximum stack depth exceeded',
      JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
      JSON_ERROR_CTRL_CHAR      => 'Unexpected control character found',
      JSON_ERROR_SYNTAX         => 'Syntax error, malformed JSON',
      JSON_ERROR_UTF8           => 'Malformed UTF-8 characters, possibly incorrectly encoded',
   ];

   /**
    * Constructor.
    *
    * @param mixed $data
    */
   final public function __construct($data = null)
   {
      $this->setData($data);
   }

   /**
    * Set data.
    *
    * @param  mixed $data
    * @return self
    */
   final public function setData($data): self
   {
      $this->data = $data;

      return $this;
   }

   /**
    * Get data.
    *
    * @return mixed
    */
   final public function getData($data)
   {
      return $this->data;
   }

   /**
    * Encoder.
    *
    * @return string
    */
   final public function encode(...$args): string
   {
      // add data as first arg
      array_unshift($args, $this->data);

      // remove useless second arg if empty
      $args = array_filter($args);

      $return = call_user_func_array('json_encode', $args);
      if ($return === false) {
         $this->setError();
      }

      return (string) $return;
   }

   /**
    * Decoder.
    *
    * @return mixed
    */
   final public function decode(...$args)
   {
      if ($this->data === '') {
         return null;
      }

      // add data as first arg
      array_unshift($args, $this->data);

      // remove useless second arg if empty
      $args = array_filter($args);

      $return = call_user_func_array('json_decode', $args);
      if (json_last_error()) {
         $this->setError();
      }

      return $return;
   }

   /**
    * Check error.
    *
    * @return bool
    */
   final public function hasError(): bool
   {
      return ($this->errorCode > 0);
   }

   /**
    * Set error.
    *
    * @return void
    */
   final private function setError()
   {
      $this->errorCode = json_last_error();
      if ($this->errorCode) {
         $this->errorMessage = isset(self::$errorMessages[$this->errorCode])
            ? self::$errorMessages[$this->errorCode]
            : 'unknown error'; // default
      }
   }

   /**
    * Get error.
    *
    * @return array
    */
   final public function getError(): array
   {
      return [
         'code' => $this->errorCode,
         'message' => $this->errorMessage,
      ];
   }

   /**
    * Get error code.
    *
    * @return int
    */
   final public function getErrorCode(): int
   {
      return $this->errorCode;
   }

   /**
    * Get error message.
    *
    * @return string
    */
   final public function getErrorMessage(): string
   {
      return $this->errorMessage;
   }
}
