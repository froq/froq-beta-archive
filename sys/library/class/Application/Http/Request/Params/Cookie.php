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

namespace Application\Http\Request\Params;

use Application\Util\Collection;

/**
 * @package    Application
 * @subpackage Application\Http\Request\Params
 * @object     Application\Http\Request\Params\Cookie
 * @author     Kerem Güneş <k-gun@mail.com>
 */
final class Cookie
   extends Collection
{
   /**
    * Constructor.
    *
    * @param array $data
    */
   final public function __construct(array $data = [])
   {
      if (empty($data)) {
         $data = $_COOKIE;
      }
      parent::__construct($data);
   }
}
