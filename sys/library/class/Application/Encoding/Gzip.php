<?php declare(strict_types=1);
namespace Application\Encoding;

final class Gzip
{
   const DEFAULT_LEVEL = -1,
         DEFAULT_MODE  = FORCE_GZIP;

   private $data;
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
      if ($this->isEncoded == false && strlen($this->data) >= $this->minlen) {
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
