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

namespace Application\Service\Protocol;

use Application\Service\{Service, ServiceInterface};

/**
 * @package    Application
 * @subpackage Application\Service\Protocol
 * @object     Application\Service\Protocol\Rest
 * @author     Kerem Güneş <k-gun@mail.com>
 * @thanks     https://spring.io/understanding/REST
 */
abstract class Rest extends Service
{
   /**
    * Service protocol.
    * @var string
    */
   protected $protocol = ServiceInterface::PROTOCOL_REST;

   /**
    * Main.
    */
   abstract public function main();

   /**
    * REST methods.
    */
   abstract public function get();
   abstract public function post();
   abstract public function put();
   abstract public function patch();
   abstract public function delete();
}
