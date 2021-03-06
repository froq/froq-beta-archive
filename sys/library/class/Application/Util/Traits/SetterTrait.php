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

namespace Application\Util\Traits;

/**
 * @package    Application
 * @subpackage Application\Util\Traits
 * @object     Application\Util\Traits\SetterTrait
 * @author     Kerem Güneş <k-gun@mail.com>
 *
 * Notice: Do not define `__set` in user objects.
 */
trait SetterTrait
{
   /**
    * Property setter (mutator).
    *
    * @param  string $name
    * @param  mixed  $value
    * @return mixed
    * @throws \Exception
    */
   public function __set($name, $value)
   {
      if (!property_exists($this, $name)) {
         throw new \Exception(sprintf(
            '`%s` property does not exists on `%s` object!', $name, get_class($this)));
      }

      $this->{$name} = $value;
   }
}
