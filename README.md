# project-name - Ein Projekt von Datana GmbH

## Platform.sh Project ID
`?????`

## Build

| `master`                                                       | `develop`                                                       |
|:---------------------------------------------------------------|:----------------------------------------------------------------|
| [![PHP][build-status-master-php]][actions]                     | [![PHP][build-status-develop-php]][actions]                     |
| [![Twig][build-status-master-twig]][actions]                   | [![Twig][build-status-develop-twig]][actions]                   |
| [![YAML][build-status-master-yaml]][actions]                   | [![YAML][build-status-develop-yaml]][actions]                   |

## Code Coverage

| `master`                                                       | `develop`                                                       |
|:---------------------------------------------------------------|:----------------------------------------------------------------|
| [![Code Coverage][coverage-status-master]][codecov]            | [![Code Coverage][coverage-status-develop]][codecov]            |

## Deployment

| `master`                                                       | `develop`                                                       |
|:---------------------------------------------------------------|:----------------------------------------------------------------|
| [![Environment][build-status-master-environment]][actions]     | [![Environment][build-status-develop-environment]][actions]     |

## MacOS PHP Setup

### PHP 8.3

```shell
$ brew install php@8.3
```

### PHPStan

```bash
$ make static-code-analysis
$ make static-code-analysis-baseline
```

Want your own phpstan configuration, e.g. to [open files in _
your_ editor](https://phpstan.org/user-guide/output-format#opening-file-in-an-editor)?

```neon
# ./phpstan.neon
includes:
    - phpstan.neon.dist

parameters:
    # using PHPStorm
    editorUrl: 'phpstorm://open?file=/path/to/your/project/%%relFile%%&line=%%line%%'

    # override project's phpstan level
    level: max
```

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

[build-status-develop-environment]: https://github.com/datana-gmbh/project-name/workflows/Environment%20(develop)/badge.svg?branch=develop
[build-status-develop-php]: https://github.com/datana-gmbh/project-name/workflows/PHP/badge.svg?branch=develop
[build-status-develop-twig]: https://github.com/datana-gmbh/project-name/workflows/Twig/badge.svg?branch=develop
[build-status-develop-yaml]: https://github.com/datana-gmbh/project-name/workflows/YAML/badge.svg?branch=develop
[build-status-master-environment]: https://github.com/datana-gmbh/project-name/workflows/Environment%20(master)/badge.svg?branch=master
[build-status-master-php]: https://github.com/datana-gmbh/project-name/workflows/PHP/badge.svg?branch=master
[build-status-master-release]: https://github.com/datana-gmbh/project-name/workflows/Release/badge.svg?branch=master
[build-status-master-twig]: https://github.com/datana-gmbh/project-name/workflows/Twig/badge.svg?branch=master
[build-status-master-yaml]: https://github.com/datana-gmbh/project-name/workflows/YAML/badge.svg?branch=master
[coverage-status-develop]: https://codecov.io/gh/datana-gmbh/project-name/branch/develop/graph/badge.svg?token=KRaxYZkSDu
[coverage-status-master]: https://codecov.io/gh/datana-gmbh/project-name/branch/master/graph/badge.svg?token=KRaxYZkSDu

[actions]: https://github.com/datana-gmbh/project-name/actions
[codecov]: https://codecov.io/gh/datana-gmbh/project-name
