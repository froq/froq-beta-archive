<?php declare(strict_types=1);
/*** IP functions. ****/

/**
 * Get real IP.
 * @return string|null
 */
function ip(): string
{
	$ip = '';
   if (null != ($ip = get_env('HTTP_X_FORWARDED_FOR'))) {
      if (strpos($ip, ',') !== false) {
         $ip = trim(end(explode(',', $ip)));
      }
   } elseif (null != ($ip = get_env('HTTP_CLIENT_IP'))) {
   	// break..
   } elseif (null != ($ip = get_env('HTTP_X_REAL_IP'))) {
   	// break..
   } elseif (null != ($ip = get_env('REMOTE_ADDR'))) {
   	// break..
   }

   return $ip;
}

/**
 * IP to long.
 * @param  string $ip
 * @return int
 */
function ip_toLong(string $ip): int
{
   return (int) sprintf('%u', ip2long($ip));
}

/**
 * IP from long.
 * @param  int $ip
 * @return string
 */
function ip_fromLong(int $ip): string
{
   return (string) long2ip($ip);
}
