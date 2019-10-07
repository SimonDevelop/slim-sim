[![version](https://img.shields.io/badge/Version-2.1.0-brightgreen.svg)](https://github.com/SimonDevelop/slim-sim/releases/tag/2.1.0)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg)](https://php.net/)
[![Minimum Node Version](https://img.shields.io/badge/node-%3E%3D%2010-brightgreen.svg)](https://nodejs.org/en/)
[![Build Status](https://travis-ci.org/SimonDevelop/slim-sim.svg?branch=master)](https://travis-ci.org/SimonDevelop/slim-sim)
[![GitHub license](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/SimonDevelop/slim-sim/blob/master/LICENSE)

![](https://github.com/SimonDevelop/slim-sim/raw/master/assets/img/logo.png)

Slim Sim is a skeleton based on PHP micro framework [Slim](https://www.slimframework.com/).

For all contributions on github, please read the document [CONTRIBUTING.md](https://github.com/SimonDevelop/slim-sim/blob/master/.github/CONTRIBUTING.md).


## Used libraries

- [twig-view](https://github.com/slimphp/Twig-View) for the views.
- [doctrine](https://github.com/doctrine/doctrine2) for the database.
- [data-fixtures](https://github.com/doctrine/data-fixtures) for the data fixture.
- [migrations](https://github.com/doctrine/migrations) for the migrations of the database.
- [validation](https://github.com/Respect/Validation) to validate the data.
- [csrf](https://github.com/slimphp/Slim-Csrf) for form security.
- [php-ref](https://github.com/digitalnature/php-ref) for an improved var_dump function.
- [phpdotenv](https://github.com/vlucas/phpdotenv) for the configuration of the environment.
- [console](https://github.com/symfony/console) for terminal commands.
- [monolog](https://github.com/Seldaek/monolog) to manage logs.
- [translation](https://github.com/symfony/translation) for the multilingual system.
- [webpack](https://github.com/webpack/webpack) for compilation and minification of files scss/sass/css/js.
- [cli-menu](https://github.com/php-school/cli-menu) for execute commands from a menu in your terminal.

#### NOTE
[cli-menu](https://github.com/php-school/cli-menu) use php posix extension which is not supported on windows, remember to delete this line in composer.json if you are under windows :
```
"php-school/cli-menu": "^3.2"
```

## Installation

```bash
$ composer create-project SimonDevelop/slim-sim <projet_name>
$ cd <projet_name>
$ composer install
$ npm install
```
Check that the `.env` file has been created, this is the configuration file of your environment or you define the connection to the database, the environment` dev` or `prod` and the activation of the twig cache.

If the file has not been created, do it manually by duplicating the `.env.example` file.

Do not forget to check that your environment configuration of your database matches well.


## Permissions

Allow the `storage` folder to write to the web server side.


## Documentation

See the [User Documentation](https://slim-sim.netlify.com/) for more details.

You using 1.x version ? See this [User Documentation](https://slim-sim-v1.netlify.com/).
