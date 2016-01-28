<?php
namespace Application\Util\Logger;

/**
 * @package    Application
 * @subpackage Application\Util\Logger
 * @object     Application\Util\Logger\Logger
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
    * Log file format, e.g 2015-01-01.txt
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
    * Log given message by level.
    *
    * @param  int    $level   Only available ALL, FAIL, WARN, INFO, DEBUG
    * @param  string $message
    * @return bool
    */
   final public function log($level, $message): bool
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
      $filename = sprintf('%s/%s.log',
         $this->directory, date($this->filenameFormat));
      // prepare message
      $message  = sprintf('[%s] %s%s',
         date('D, d M Y H:i:s O'), $messagePrepend, trim($message) ."\n");

      return (bool) file_put_contents($filename, $message, LOCK_EX | FILE_APPEND);
   }
}
