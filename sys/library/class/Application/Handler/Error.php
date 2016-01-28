<?php declare(strict_types=1);
namespace Application\Handler;

final class Error
{
   final public static function handler()
   {
      return function($ecode, $emesg, $efile, $eline) {
         // error was suppressed with the @-operator
         if (!$ecode || !($ecode & error_reporting())) {
            return;
         }

         $error = null;
         // check error type
         switch ($ecode) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_CORE_WARNING:
            case E_COMPILE_ERROR:
            case E_COMPILE_WARNING:
            case E_STRICT:
               $error = sprintf('Runtime error in %s:%s ecode[%s] emesg[%s]',
                  $efile, $eline,  $ecode, $emesg);
               break;
            case E_RECOVERABLE_ERROR:
               $error = sprintf('E_RECOVERABLE_ERROR in %s:%s ecode[%s] emesg[%s]',
                  $efile, $eline, $ecode, $emesg);
               break;
            case E_USER_ERROR:
               $error = sprintf('E_USER_ERROR in %s:%s ecode[%s] emesg[%s]',
                  $efile, $eline, $ecode, $emesg);
               break;
            case E_USER_WARNING:
               $error = sprintf('E_USER_WARNING in %s:%s ecode[%s] emesg[%s]',
                  $efile, $eline, $ecode, $emesg);
               break;
            case E_USER_NOTICE:
               $error = sprintf('E_USER_NOTICE in %s:%s ecode[%s] emesg[%s]',
                  $efile, $eline, $ecode, $emesg);
               break;
            default:
               $error = sprintf('Unknown error in %s:%s ecode[%s] emesg[%s]',
                  $efile, $eline, $ecode, $emesg);
         }

         // throw! exception handler will catch it
         if ($error) {
            throw new \ErrorException($error, $ecode);
         }

         // don't execute php internal error handler
         return true;
      };
   }
}
