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

namespace Application\Http;

use \Application\Util\Traits\GetterTrait as Getter;

/**
 * @package    Application
 * @subpackage Application\Http
 * @object     Application\Http\Client
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Client
{
   /**
    * Getter.
    * @object Application\Util\Traits\GetterTrait
    */
   use Getter;

   /**
    * Client IP.
    * @var string
    */
   private $ip;

   /**
    * Client locale.
    * @var string
    */
   private $locale;

   /**
    * Client language.
    * @var string
    */
   private $language;

   /**
    * Constructor.
    */
   final public function __construct()
   {
      $app = app();

      // set ip
      $this->ip = ip();

      // set language
      $this->language = $app->config->get('app.language');
      if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
         $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
         if (in_array($language, $app->config->get('app.languages'))) {
            $this->language = $language;
         }
      }

      // set locale
      $this->locale = sprintf('%s_%s', $this->language, strtoupper($this->language));
      if (!array_key_exists($this->locale, $app->config->get('app.locales'))) {
         $this->locale = $app->config->get('app.locale');
      }
   }
}
