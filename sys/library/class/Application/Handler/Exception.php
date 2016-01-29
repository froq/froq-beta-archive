<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <http://qeremy.com>
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
 * @object     Application\Handler\Exception
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Exception
{
   /**
    * Exception handler.
    *
    * @return void
    */
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
