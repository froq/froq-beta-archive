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

/*** "IP" function module. ***/

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
   	// ok..
   } elseif (null != ($ip = get_env('HTTP_X_REAL_IP'))) {
   	// ok..
   } elseif (null != ($ip = get_env('REMOTE_ADDR'))) {
   	// ok..
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
