<?php
/*** Is function module. ***/

/**
 * Check env is local.
 * @return bool
 */
function is_local() {
    if (defined('local')) {
        return (local === true);
    }
    return ((bool) strstr($_SERVER['SERVER_NAME'], '.local'));
}
