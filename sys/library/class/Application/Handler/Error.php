<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <k-gun@mail.com>
 *
 * GNU General Public License v3.0
 *    <http://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Application\Handler;

/**
 * @package    Application
 * @subpackage Application\Handler
 * @object     Application\Handler\Error
 * @author     Kerem Güneş <k-gun@mail.com>
 */
final class Error
{
   /**
    * Error handler.
    *
    * @return mixed
    */
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
