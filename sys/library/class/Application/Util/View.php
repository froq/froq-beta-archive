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

namespace Application\Util;

use Application\Service\ServiceInterface;

/**
 * @package    Application
 * @subpackage Application\Util
 * @object     Application\Util\View
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class View
{
   /**
    * Partial files.
    * @const string
    */
   const PARTIAL_HEAD = 'partial/head',
        	PARTIAL_FOOT = 'partial/foot';

   /**
    * Application object.
    * @var Application\Application
    */
   private $app;

   /**
    * Service object.
    * @var Application\Service\ServiceInterface
    */
   private $service;

   /**
    * Constructor.
    *
    * @param Application\Service\ServiceInterface $service
    */
   final public function __construct(ServiceInterface $service)
   {
      $this->app = $service->app;
      $this->service = $service;
   }

   /**
    * Display/render view file.
    *
    * @param  string $file
    * @param  array  $data
    * @return void
    */
   final public function display(string $file, array $data = null)
   {
      $this->includeFile($this->prepareFile($file), $data);
   }

   /**
    * Display/render partial/header file.
    *
    * @param  array $data
    * @return void
    */
   final public function displayHead(array $data = null)
   {
      // check local service file
      $file = $this->prepareFile(self::PARTIAL_HEAD, false);
      if (!is_file($file)) {
         // look up for global service file
         $file = $this->prepareFileGlobal(self::PARTIAL_HEAD);
      }

      $this->includeFile($file, $data);
   }

   /**
    * Display/render partial/footer file.
    *
    * @param  array $data
    * @return void
    */
   final public function displayFoot(array $data = null)
   {
      // check local service file
      $file = $this->prepareFile(self::PARTIAL_FOOT, false);
      if (!is_file($file)) {
         // look up for global service file
         $file = $this->prepareFileGlobal(self::PARTIAL_FOOT);
      }

      $this->includeFile($file, $data);
   }

   /**
    * Include file.
    *
    * @param  string $file
    * @param  array  $data
    * @return void
    */
   final public function includeFile(string $file, array $data = null)
   {
      extract((array) $data);

      include($file);
   }

   /**
    * Prepare file path.
    *
    * @param  string $file
    * @param  bool   $fileCheck
    * @return string
    */
   final public function prepareFile(string $file, bool $fileCheck = true)
   {
      // default file given
      if ($file[0] == '.') {
         $file = sprintf('%s.php', $file);
      } else {
         $file = sprintf('./app/service/%s/view/%s.php', $this->service->name, $file);
      }

      if ($fileCheck && !is_file($file)) {
         throw new \RuntimeException('View file not found! file: '. $file);
      }

      return $file;
   }

   /**
    * Prepare global file path.
    *
    * @param  string $file
    * @param  bool   $fileCheck
    * @return string
    */
   final public function prepareFileGlobal(string $file, bool $fileCheck = true)
   {
      $file = sprintf('./app/service/view/%s.php', $file);
      if ($fileCheck && !is_file($file)) {
         throw new \RuntimeException('View file not found! file: '. $file);
      }

      return $file;
   }
}
