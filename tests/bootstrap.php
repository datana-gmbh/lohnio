<?php

declare(strict_types=1);
use App\Bridge\Faker\ExtendedGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Zenstruck\Foundry\Instantiator;
use Zenstruck\Foundry\Test\TestState;

require_once __DIR__.'/../config/bootstrap.php';

$filesystem = new Filesystem();

$filesystem->remove(__DIR__.'/../var/cache/test');

TestState::configure(
    instantiator: (new Instantiator())
        ->withoutConstructor()
        ->allowExtraAttributes()
        ->alwaysForceProperties(),
    faker: new ExtendedGenerator(),
);
