<?php declare(strict_types=1);
namespace Application\Handler;

final class Shutdown
{
   final public static function handler()
   {
      return function() {
         $error = error_get_last();
         if (isset($error['type']) && $error['type'] == E_ERROR) {
            // pre($error);
            $error = sprintf('Shutdown! E_ERROR in %s:%d ecode[%d] emesg[%s]',
               $error['file'], $error['line'], $error['type'], $error['message']);

            // easy boy!
            $app = app();
            $app->logger->logFail($error);

            // handle response properly
            $app->response->setStatus(500);
            $app->response->setContentType('none');
            $app->response->send();

            // reset error display option
            $opt = get_global('display_errors');
            if ($opt !== null) {
               ini_set('display_errors', $opt);
            }
         }
      };
   }
}
