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

namespace Application\Http\Uri;

/**
 * @package    Application
 * @subpackage Application\Http\Uri
 * @object     Application\Http\Uri\UriPath
 * @author     Kerem Güneş <k-gun@mail.com>
 */
final class UriPath
{
   /**
    * URI path.
    * @var string
    */
   private $path;

   /**
    * URI segments.
    * @var array
    */
   private $segments = [];

   /**
    * Constructor.
    */
   final public function __construct()
   {
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      if ($path != '') {
         $this->path = $path;
         $this->segments = self::generateSegments($path);
      }
   }

   /**
    * Check root.
    *
    * @return bool
    */
   final public function isRoot(): bool
   {
      return ($this->path == '/');
   }

   /**
    * Get path.
    *
    * @return string
    */
   final public function getPath(): string
   {
      return $this->path;
   }

   /**
    * Get segment.
    *
    * @param  int   $i
    * @param  mixed $def
    * @return mixed
    */
   final public function getSegment($i, $def = null)
   {
      if (isset($this->segments[$i])) {
         return $this->segments[$i];
      }

      return $def;
   }

   /**
    * Get all segments.
    *
    * @return array
    */
   final public function getSegmentAll(): array
   {
      return $this->segments;
   }

   /**
    * Shortcut for self::getSegment().
    *
    * @see self::getSegment()
    */
   final public function segment($i, $def = null)
   {
      return $this->getSegment($i, $def);
   }

   /**
    * Generate segments.
    *
    * @param  string $path
    * @param  string $pathRoot
    * @return array
    */
   final public static function generateSegments($path, string $pathRoot = null): array
   {
      // remove path root
      if ($pathRoot != '' && $pathRoot != '/') {
         $path = preg_replace('~^'. preg_quote($pathRoot) .'~', '', $path);
      }

      return array_filter(array_map('trim',
         preg_split('~/+~', $path, -1, PREG_SPLIT_NO_EMPTY)));
   }
}
