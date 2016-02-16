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

namespace Application\Encoding;

/**
 * @package    Application
 * @subpackage Application\Encoding
 * @object     Application\Encoding\Gzip
 * @author     Kerem Güneş <k-gun@mail.com>
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
   private $dataMinlen = 1024;

   /**
    * GZip level.
    * @var int
    */
   private $level;

   /**
    * GZip mode.
    * @var int
    */
   private $mode;

   /**
    * Encoded flag.
    * @var bool
    */
   private $isEncoded = false;

   /**
    * Constructor.
    *
    * @var string $data
    * @var int    $level
    * @var int    $mode
    */
   final public function __construct(string $data = null,
      int $level = self::DEFAULT_LEVEL, int $mode = self::DEFAULT_MODE)
   {
      $this->setData($data)
         ->setLevel($level)
         ->setMode($mode);
   }

   /**
    * Set data.
    *
    * @param string $data
    */
   final public function setData(string $data = null): self
   {
      $this->data = (string) $data;

      return $this;
   }

   /**
    * Set data minlen.
    *
    * @param int $dataMinlen
    */
   final public function setDataMinlen(int $dataMinlen): self
   {
      $this->dataMinlen = $dataMinlen;

      return $this;
   }

   /**
    * Set level.
    * @param int $level
    */
   final public function setLevel(int $level): self
   {
      $this->level = $level;

      return $this;
   }

   /**
    * Set mode.
    * @param int $mode
    */
   final public function setMode(int $mode): self
   {
      $this->mode = $mode;

      return $this;
   }

   /**
    * Get data.
    *
    * @return string
    */
   final public function getData(): string
   {
      return $this->data;
   }

   /**
    * Get data minlen.
    *
    * @return int
    */
   final public function getDataMinlen(): int
   {
      return $this->dataMinlen;
   }

   /**
    * Get level.
    *
    * @return int
    */
   final public function getLevel(): int
   {
      return $this->level;
   }

   /**
    * Get mode.
    *
    * @return int
    */
   final public function getMode(): int
   {
      return $this->mode;
   }

   /**
    * Encode.
    *
    * @return string
    */
   final public function encode(): string
   {
      if (!$this->isEncoded) {
         $this->isEncoded = true;
         $this->data = gzencode($this->data, $this->level, $this->mode);
      }

      return $this->data;
   }

   /**
    * Decoder.
    *
    * @return string
    */
   final public function decode(): string
   {
      if ($this->isEncoded) {
         $this->isEncoded = false;
         $this->data = gzdecode($this->data);
      }

      return $this->data;
   }

   /**
    * Check is encoded.
    *
    * @return bool
    */
   final public function isEncoded(): bool
   {
      return $this->isEncoded;
   }

   /**
    * Check minlen limit to encode.
    *
    * @return bool
    */
   final public function isDataMinlenOK(): bool
   {
      return (strlen($this->data) >= $this->dataMinlen);
   }
}
