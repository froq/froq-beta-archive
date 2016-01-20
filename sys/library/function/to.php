<?php declare(strict_types=1);
/*** To function module. ***/

/**
 * Convert an iterable to array.
 * @param  iter $input
 * @param  bool $deep
 * @return array
 */
function to_array($input, bool $deep = true): array {
   $input = (array) $input;
   if ($deep) {
      foreach ($input as $key => $value) {
         $input[$key] = is_iter($value)
            ? __function__($value, $deep) : $value;
      }
   }
   return $input;
}

/**
 * Convert an iterable to object.
 * @param  iter $input
 * @param  bool $deep
 * @return array
 */
function to_object($input, bool $deep = true): \stdClass {
   $input = (object) $input;
   if ($deep) {
      foreach ($input as $key => $value) {
         $input->{$key} = is_iter($value)
            ? __function__($value, $deep) : $value;
      }
   }
   return $input;
}
