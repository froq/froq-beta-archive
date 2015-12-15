<?php declare(strict_types=1);
/*** IP functions. ****/

/**
 * Get real IP.
 * @return string|null
 */
function ip(): string {
    if (($ip = get_env('HTTP_X_FORWARDED_FOR')) != '') {
        if (strpos($ip, ',') !== false) {
            $ips = explode(',', $ip);
            return trim(end($ips));
        }
        return $ip;
    }
    if (($ip = get_env('HTTP_CLIENT_IP')) != '') {
        return $ip;
    }
    if (($ip = get_env('HTTP_X_REAL_IP')) != '') {
        return $ip;
    }
    if (($ip = get_env('REMOTE_ADDR')) != '')    {
        return $ip;
    }
    return '';
}

/**
 * IP to long.
 * @param  string $ip
 * @return int
 */
function ip_toLong(string $ip): int {
    return (int) sprintf('%u', ip2long($ip));
}

/**
 * IP from long.
 * @param  int $ip
 * @return string
 */
function ip_fromLong(int $ip): string {
    return (string) long2ip($ip);
}
