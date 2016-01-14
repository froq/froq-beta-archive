<?php declare(strict_types=1);
namespace Application\Handler;

final class Shutdown
{
    final public static function handler()
    {
        return function() {
            // $error = error_get_last();
            // if (isset($error['type']) && $error['type'] == E_ERROR) {
            //     $error = sprintf('Shutdown! E_ERROR in %s:%d errno[%d] errmsg[%s]',
            //         $error['file'], $error['line'], $error['type'], $error['message']);

            //     // @todo
            //     $app = app();
            //     $app->logger->logFail($e);
            // }
        };
    }
}
