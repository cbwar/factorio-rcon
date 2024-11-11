<?php

use Cbwar\FactorioRcon\Command\RConCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$app = new Application('cbwar/factorio-rcon', '1.0');
$app->add(new RConCommand());
$app->run();
