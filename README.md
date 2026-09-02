# CodeIgniter HTMX

A set of methods for `IncomingRequest`, `Response` and `RedirectResponse` classes to help you work with [htmx](https://four.htmx.org/) fluently in CodeIgniter 4 framework.

It also provides some additional help with **handling errors** and **Debug Toolbar** in development mode as well as support for **view fragments**.

This version targets the htmx 4 request/response model and event lifecycle. Applications that still use htmx 2 should use the `v2` branch.

[![PHPUnit](https://github.com/michalsn/codeigniter-htmx/actions/workflows/phpunit.yml/badge.svg)](https://github.com/michalsn/codeigniter-htmx/actions/workflows/phpunit.yml)
[![PHPStan](https://github.com/michalsn/codeigniter-htmx/actions/workflows/phpstan.yml/badge.svg)](https://github.com/michalsn/codeigniter-htmx/actions/workflows/phpstan.yml)
[![Deptrac](https://github.com/michalsn/codeigniter-htmx/actions/workflows/deptrac.yml/badge.svg)](https://github.com/michalsn/codeigniter-htmx/actions/workflows/deptrac.yml)
[![Coverage Status](https://coveralls.io/repos/github/michalsn/codeigniter-htmx/badge.svg?branch=develop)](https://coveralls.io/github/michalsn/codeigniter-htmx?branch=develop)

![PHP](https://img.shields.io/badge/PHP-%5E8.2-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-%5E4.7-blue)

## Installation

    composer require michalsn/codeigniter-htmx

> [!NOTE]
> This package does not install the browser-side htmx library. Install htmx 4 explicitly - for example, `npm install htmx.org@4` - and include it in your page. An unversioned npm install may still resolve to htmx 2 during the htmx 4 release transition.

## Docs

https://michalsn.github.io/codeigniter-htmx/

## Demos

https://github.com/michalsn/codeigniter-htmx-demo
