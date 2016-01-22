<?php declare(strict_types=1);
/*** Is function module. ***/

/**
 * Check env is local.
 * @return bool
 */
function is_local(): bool
{
   if (defined('local')) {
      return (local === true);
   }
   return ((bool) strstr($_SERVER['SERVER_NAME'], '.local'));
}

/**
 * Check callee allowed.
 * @param  string      $filePath
 * @param  array|null  &$callee
 * @param  string|null &$error
 * @return bool
 */
function is_callee_allowed(string $filePath, array &$callee = null, string &$error = null): bool
{
   $callee = get_callee(4);
   if (strpos($callee['file'], $filePath)) {
      $error = sprintf('Call from bad scope! class: %s::%s() file: %s:%d',
         $callee['class'], $callee['function'], $callee['file'], $callee['line']);
      return false;
   }

   return true;
}

/**
 * Check var is iterable.
 * @param  mixed $input
 * @return bool
 */
function is_iter($input): bool
{
   return is_array($input)
      || ($input instanceof \stdClass)
      || ($input instanceof \Traversable);
}
