<?php declare(strict_types=1);
namespace Application\Encryption\Oneway;

/**
 * @package    Application
 * @subpackage Application\Encryption\Oneway
 * @object     Application\Encryption\Oneway\Password
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Password
{
   /**
    * Crypt input.
    * @var string
    */
   private $input;

   /**
    * Hashing algorithm.
    * @var int
    */
   private $algo = PASSWORD_DEFAULT;

   /**
    * Hashing options.
    * @var array
    */
   private $options = ['cost' => 10];

   /**
    * Constructor.
    *
    * @param string $input
    * @param int    $algo
    * @param array  $options
    */
   final public function __construct(string $input, int $algo = null, array $options = [])
   {
      $this->input = $input;

      if ($algo) {
         $this->algo = $algo;
      }

      $this->options = array_merge($this->options, $options);
   }

   /**
    * Generate a hash.
    *
    * @param  string $salt
    * @return string
    */
   final public function hash(string $salt = null): string
   {
      if (trim($salt)) {
         $this->options['salt'] = $salt;
      }

      return password_hash($this->input, $this->algo, $this->options);
   }

   /**
    * Verify a hash.
    *
    * @param  string $hash
    * @return bool
    */
   final public function verify(string $hash): bool
   {
      return password_verify($this->input, $hash);
   }
}
