# CONTRIBUTING

We are using [GitHub Actions](https://github.com/features/actions) as a continuous integration system.

For details, take a look at the following workflow configuration files:

- [`workflows/ci-php.yaml`](workflows/ci-php.yaml)
- [`workflows/ci-twig.yaml`](workflows/ci-twig.yaml)
- [`workflows/ci-yaml.yaml`](workflows/ci-yaml.yaml)
- [`workflows/environment-delete.yaml`](workflows/environment-delete.yaml)
- [`workflows/environment-develop.yaml`](workflows/environment-develop.yaml)
- [`workflows/environment-pull-request.yaml`](workflows/environment-pull-request.yaml)
- [`workflows/environment-synchronize.yaml`](workflows/environment-synchronize.yaml)
- [`workflows/triage.yaml`](workflows/triage.yaml)

### Coding Standards

We are using [`ergebnis/composer-normalize`](https://github.com/ergebnis/composer-normalize) to normalize `composer.json`.

We are using [`friendsofphp/php-cs-fixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to enforce coding standards in PHP files.

Run

```
$ make cs
```

to automatically fix coding standard violations.

### Dependency Analysis

We are using [`maglnet/composer-require-checker`](https://github.com/maglnet/ComposerRequireChecker) to prevent the use of unknown symbols in production code.

Run

```
$ make dependency-analysis
```

to run a dependency analysis.

### Doctrine

We are using [`doctrine/orm`](https://github.com/doctrine/orm) as an object-relational mapper.

Run

```
$ make doctrine
```

to recreate a local test database from entity mappings and to validate the schema.

### Static Code Analysis

We are using [`phpstan/phpstan`](https://github.com/phpstan/phpstan) to statically analyze the code.

Run

```
$ make static-code-analysis
```

to run a static code analysis.

We are also using the baseline features of [`phpstan/phpstan`](https://medium.com/@ondrejmirtes/phpstans-baseline-feature-lets-you-hold-new-code-to-a-higher-standard-e77d815a5dff).

Run

```
$ make static-code-analysis-baseline
```

to regenerate the baselines in

- [`../backend/phpstan-default-baseline.neon`](../backend/phpstan-default-baseline.neon)

:exclamation: Ideally, the baseline should shrink over time.

### Tests

We are using [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit) to drive the development.

Run

```
$ make tests
```

to run all the tests.

### Extra lazy?

Run

```
$ make
```

to enforce coding standards, run a static code analysis and run the tests!

### Help

:bulb: Run

```
$ make help
```

to display a list of available targets with corresponding descriptions.
