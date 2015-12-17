<?php defined('root') or die('Access denied!'); ?>

<?php if (isset($error, $error_detail)): ?>

    <h1><?php print $error; ?></h1>
    <h3><?php print $error_detail; ?></h3>

<?php else: ?>

    <h1>Error!</h1>
    <h3>Unknown error occurred.</h3>

<?php endif; ?>
