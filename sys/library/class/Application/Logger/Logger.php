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

namespace Application\Logger;

/**
 * @package    Application
 * @subpackage Application\Logger\Logger
 * @object     Application\Logger\Logger\Logger
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Logger
{
   /**
    * Log all events (FAIL | WARN | INFO | DEBUG).
    * @const int
    */
   const ALL = 30;

   /**
    * Log only error events.
    * @const int
    */
   const FAIL = 2;

   /**
    * Log only warning events.
    * @const int
    */
   const WARN = 4;

   /**
    * Log only informal events.
    * @const int
    */
   const INFO = 8;

   /**
    * Log only debugging events.
    * @const int
    */
   const DEBUG = 16;

   /**
    * No log.
    * @const int
    */
   const NONE = 0;

   /**
    * Log level, disabled as default.
    * @var int
    */
   protected $level = 0;

   /**
    * Log directory.
    * @var string
    */
   protected $directory;

   /**
    * Aims some performance, escaping to call "is_dir" function.
    * @var bool
    */
   protected $directoryChecked = false;

   /**
    * Log file format with a valid date format (e.g 2015-01-01.txt)
    * @var string
    */
   protected $filenameFormat = 'Y-m-d';

   /**
    * Set log level.
    *
    * @param  int $level  Must be a valid level like self::ALL
    * @return self
    */
   final public function setLevel($level): self
   {
      $this->level = $level;

      return $this;
   }

   /**
    * Get log level.
    *
    * @return int
    */
   final public function getLevel(): int
   {
      return $this->level;
   }

   /**
    * Set log directory.
    *
    * @param  string $directory
    * @return self
    */
   final public function setDirectory($directory): self
   {
      $this->directory = $directory;

      return $this;
   }

   /**
    * Get log directory.
    *
    * @return string
    */
   final public function getDirectory(): string
   {
      return $this->directory;
   }

   /**
    * Set log filename format.
    *
    * @param  string $filenameFormat
    * @return self
    */
   final public function setFilenameFormat($filenameFormat): self
   {
      $this->filenameFormat = $filenameFormat;

      return $this;
   }

   /**
    * Get log filename format.
    *
    * @return string
    */
   final public function getFilenameFormat(): string
   {
      return $this->filenameFormat;
   }

   /**
    * Check log directory, if not exists create it.
    *
    * @throws \RuntimeException
    * @return bool
    */
   public function checkDirectory(): bool
   {
      if (empty($this->directory)) {
         throw new \RuntimeException(
            'Log directory is not defined in given configuration! '.
            'Define it using `query_log_directory` key to activate logging.');
      }

      $this->directoryChecked = $this->directoryChecked ?: is_dir($this->directory);
      if (!$this->directoryChecked) {
         $this->directoryChecked = mkdir($this->directory, 0755, true);

         // !!! notice !!!
         // set your log dir secure
         file_put_contents($this->directory .'/index.php',
            "<?php header('HTTP/1.1 403 Forbidden'); ?>");
         // this action is for only apache, see nginx configuration here:
         // http://nginx.org/en/docs/http/ngx_http_access_module.html
         file_put_contents($this->directory .'/.htaccess',
            "Order deny,allow\r\nDeny from all");
      }

      return $this->directoryChecked;
   }

   /**
    * Log given message by level.
    *
    * @param  int   $level   Only available ALL, FAIL, WARN, INFO, DEBUG
    * @param  mixed $message
    * @return bool
    */
   final public function log(int $level, $message): bool
   {
      // no log command
      if (!$level || ($level & $this->level) == 0) {
         return;
      }

      // ensure log directory
      $this->checkDirectory();

      // prepare message prepend
      $messagePrepend = '';
      switch ($level) {
         case self::FAIL:
            $messagePrepend = '[FAIL] ';
            break;
         case self::INFO:
            $messagePrepend = '[INFO] ';
            break;
         case self::WARN:
            $messagePrepend = '[WARN] ';
            break;
         case self::DEBUG:
            $messagePrepend = '[DEBUG] ';
            break;
      }

      // prepare filename
      $filename = sprintf('%s/%s.log', $this->directory, date($this->filenameFormat));

      // just for local
      if (is_local()) {
         chmod($filename, 0666);
      }

      if ($message instanceof \Throwable) {
         $message = get_class($message) ." thrown in '". $message->getFile() .":".
            $message->getLine() ."' with message '". $message->getMessage() ."'.\n".
            $message->getTraceAsString() ."\n";
      } elseif (is_array($message) || is_object($message)) {
         $message = json_encode($message);
      }

      // prepare message
      $message  = sprintf("[%s] %s%s\n---\n",
         date('D, d M Y H:i:s O'), $messagePrepend, trim($message));

      return (bool) file_put_contents($filename, $message, LOCK_EX | FILE_APPEND);
   }

   /**
    * Shortcut for fail logs.
    *
    * @param  mixed $message
    * @return bool
    */
   final public function logFail($message): bool
   {
      return $this->log(self::FAIL, $message);
   }

   /**
    * Shortcut for warn logs.
    *
    * @param  mixed $message
    * @return bool
    */
   final public function logWarn($message): bool
   {
      return $this->log(self::WARN, $message);
   }

   /**
    * Shortcut for info logs.
    *
    * @param  mixed $message
    * @return bool
    */
   final public function logInfo($message): bool
   {
      return $this->log(self::INFO, $message);
   }

   /**
    * Shortcut for debug logs.
    *
    * @param  mixed $message
    * @return bool
    */
   final public function logDebug($message): bool
   {
      return $this->log(self::DEBUG, $message);
   }
}
