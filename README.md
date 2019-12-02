# Advent of Code 2019
http://adventofcode.com/2019

Solutions with PHP 7.2 and Symfony

## Install

  * `ddev start`
  * `ddev ssh`
  * `composer install`
  * `cp .env.local.dist .env.local`
    * set `AOC_SESSION` to the value of your `session` cookie in order to download the days puzzle input
  * `php bin/console doctrine:migrations:migrate`
  * `yarn install`
  * `yarn encore dev`
  * visit https://adventofcode-2019.ddev.site/ and select a day

### Usage
  * Add a new solution: `php bin/console make:daysolver`

## Inital setup
  * `ddev config` 
  * `ddev ssh`
  * `composer create-project symfony/website-skeleton adventofcode-2019`
  * `composer require annotations`
  * `php bin/console make:controller web`
  * `php bin/console make:controller puzzle --no-template`
  * `php bin/console make:controller day01 --no-template`
  * `composer require symfony/webpack-encore-bundle`
  * `yarn install`
  * `yarn add jquery`
