<?php declare(strict_types=1);
namespace Application\Handler;

final class Exception
{
    final public static function handler()
    {
        return function(\Throwable $e) {
            if (is_local()) {
                throw $e;
            }

            // @todo
            $app = app();
            $app->logger->logFail($e);
        };
    }
}
