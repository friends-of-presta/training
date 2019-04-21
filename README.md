## Module for showcasing new features of PrestaShop

### Requirements

1. Composer, see [Composer](https://getcomposer.org/) to learn more
2. Yarn, see [Yarn](https://yarnpkg.com/lang/en/) to learn more
 
### How to install

1. Download or clone module into `modules` directory of your PrestaShop installation
2. Make sure module directory is named `pstraining`
3. `cd` into module's directory and run following commands:
	 - `composer dumpautoload` to generate autoloader for module
	 - `yarn install` to install Encore (see [Webpack Encore](http://symfony.com/doc/current/frontend.html) to learn more about Encore)
	 - `yarn encore dev`  to compile assets for development environment
	 - (optional) `yarn encore production` to compile assets for production use (e.g. before submitting module to Addons Marketplace or installing on live shop)
4. Install module from Back Office
 
### How to run tests
 
This module is using PHPUnit that comes with PrestaShop. To Run tests follow instructions below.

1. Make sure you are inside module directory
2. Run `./../../vendor/bin/phpunit` 
