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
 * @object     Application\Handler\Shutdown
 * @author     Kerem Güneş <k-gun@mail.com>
 */
final class Shutdown
{
   /**
    * Shutdown handler.
    *
    * @return void
    */
   final public static function handler()
   {
      return function() {
         $error = error_get_last();
         if (isset($error['type']) && $error['type'] == E_ERROR) {
            // pre($error);
            $error = sprintf('Shutdown! E_ERROR in %s:%d ecode[%d] emesg[%s]',
               $error['file'], $error['line'], $error['type'], $error['message']);

            // easy boy!
            $app = app();
            $app->logger->logFail($error);

            // handle response properly
            $app->response->setStatus(500);
            $app->response->setContentType('none');
            $app->response->send();

            // reset error display option
            $opt = get_global('display_errors');
            if ($opt !== null) {
               ini_set('display_errors', $opt);
            }
         }
      };
   }
}
