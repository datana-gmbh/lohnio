<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;
use ComposerUnused\ComposerUnused\Configuration\PatternFilter;
use Webmozart\Glob\Glob;

return static fn (Configuration $config): Configuration => $config
    ->addPatternFilter(PatternFilter::fromString('/datana-gmbh\/doctrine-.*/'))
    ->addNamedFilter(NamedFilter::fromString('datana-gmbh/logz-io-handler'))
    ->addNamedFilter(NamedFilter::fromString('ext-pdo_pgsql'))
    ->addNamedFilter(NamedFilter::fromString('symfony/flex'))
    ->addNamedFilter(NamedFilter::fromString('symfony/var-dumper'))
    ->addNamedFilter(NamedFilter::fromString('symfony/expression-language'))
    ->addNamedFilter(NamedFilter::fromString('oskarstark/trimmed-non-empty-string'))
    ->addNamedFilter(NamedFilter::fromString('oskarstark/symfony-http-responder'))
    ->addNamedFilter(NamedFilter::fromString('thecodingmachine/safe'))
    ->addNamedFilter(NamedFilter::fromString('webmozart/assert'))
    ->setAdditionalFilesFor('datana-gmbh/project-name', [
        __FILE__,
        ...array_merge(
            Glob::glob(__DIR__.'/bin/*.php'),
            Glob::glob(__DIR__.'/config/*.php'),
            Glob::glob(__DIR__.'/public/*.php'),
            Glob::glob(__DIR__.'/templates/*.php'),
        ),
    ]);
