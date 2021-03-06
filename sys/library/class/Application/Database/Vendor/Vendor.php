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

namespace Application\Database\Vendor;

use Application\Util\Traits\SingleTrait as Single;

/**
 * @package    Application
 * @subpackage Application\Database\Vendor
 * @object     Application\Database\Vendor\Vendor
 * @author     Kerem Güneş <k-gun@mail.com>
 */
abstract class Vendor
{
   /**
    * Single.
    * @object Application\Util\Traits\SingleTrait
    */
   use Single;

   /**
    * Database object.
    * @var Application\Database
    */
   protected $db;

   /**
    * Proxy call method to database object methods.
    *
    * @param  string $method
    * @param  array  $arguments
    * @return mixed
    */
   final public function __call($method, array $arguments)
   {
      return call_user_func_array([$this->db, $method], $arguments);
   }
}
