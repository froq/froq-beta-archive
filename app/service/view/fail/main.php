<?php defined('root') or die('Access denied!'); ?>

<?php if (isset($error, $error_detail)): ?>

    <h1><?php print $error; ?></h1>
    <p><?php print $error_detail; ?></p>

<?php else: ?>

    <h1>Error!</h1>
    <p>Unknown error occurred.</p>

<?php endif; ?>
