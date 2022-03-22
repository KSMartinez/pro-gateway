<?php declare(strict_types = 1);

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
return new Application($kernel);