<?php

include __DIR__ . '/vendor/sharoff45/library-cs-fixer/config.php';

$finder = getFinder(__DIR__);
$config = getConfig($finder);

return $config;
