<?php declare(strict_types=1);
namespace Application\Handler;

final class Exception
{
   final public static function handler()
   {
      return function(\Throwable $e) {
         // if not local no error display (so set & store option)
         if (!is_local()) {
            set_global('display_errors', ini_set('display_errors', '0'));
         }

         // will be catched in shutdown handler
         throw $e;
      };
   }
}
