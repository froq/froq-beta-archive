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

namespace Application\Encoding;

/**
 * @package    Application
 * @subpackage Application\Encoding
 * @object     Application\Encoding\Gzip
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Gzip
{
   /**
    * GZip level & mode.
    * @const int, int
    */
   const DEFAULT_LEVEL = -1,
         DEFAULT_MODE  = FORCE_GZIP;

   /**
    * Data.
    * @var string
    */
   private $data;

   /**
    * Data minlen for encoding.
    * @var int
    */
   private $dataMinLength = 1024;
   private $level;
   private $mode;
   private $isEncoded = false;

   final public function __construct(string $data = null,
      int $level = self::DEFAULT_LEVEL, int $mode = self::DEFAULT_MODE)
   {
      $this->setData($data)
         ->setLevel($level)
         ->setMode($mode);
   }

   final public function setData(string $data = null): self
   {
      $this->data = (string) $data;
      return $this;
   }

   final public function setDataMinLength(int $dataMinLength): self
   {
      $this->dataMinLength = $dataMinLength;
      return $this;
   }

   final public function setLevel(int $level): self
   {
      $this->level = $level;
      return $this;
   }

   final public function setMode(int $mode): self
   {
      $this->mode = $mode;
      return $this;
   }

   final public function getData(): string
   {
      return $this->data;
   }

   final public function getDataMinLength(): int
   {
      return $this->dataMinLength;
   }

   final public function getLevel(): int
   {
      return $this->level;
   }

   final public function getMode(): int
   {
      return $this->mode;
   }


   final public function encode(): string
   {
      if ($this->isEncoded == false && strlen($this->data) >= $this->dataMinLength) {
         $this->isEncoded = true;
         $this->data = gzencode($this->data, $this->level, $this->mode);
      }

      return $this->data;
   }

   final public function decode(): string
   {
      if ($this->isEncoded == true) {
         $this->isEncoded = false;
         $this->data = gzdecode($this->data);
      }

      return $this->data;
   }

   final public function isEncoded(): bool
   {
      return $this->isEncoded;
   }
}
