# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]

## [1.5.0](https://github.com/michalsn/codeigniter-htmx/compare/v1.4.0...v1.5.0) - 2024-06-10

### Enhancements

- feat: add HTML formatter

## [1.4.0](https://github.com/michalsn/codeigniter-htmx/compare/v1.3.0...v1.4.0) - 2023-11-23

### Enhancements

- feat: add `HX-Reselect` header support

## [1.3.0](https://github.com/michalsn/codeigniter-htmx/compare/v1.2.2...v1.3.0) - 2023-11-20

### Enhancements

- feat: configurable view decorators

## [1.2.2](https://github.com/michalsn/codeigniter-htmx/compare/v1.2.1...v1.2.2) - 2023-08-26

### Fixed
- Update `Toolbar` class for changes in CI 4.4.

## [1.2.1](https://github.com/michalsn/codeigniter-htmx/compare/v1.2.0...v1.2.1) - 2023-06-17

### Fixed
- In some cases `view_fragment` wasn't parsed properly.

## [1.2.0](https://github.com/michalsn/codeigniter-htmx/compare/v1.1.0...v1.2.0) - 2023-06-06

### Enhancements
- Add support for displaying the Debug Toolbar after `htmx` request.

### Changed
- The added JavaScript is now placed in the `head` tag.

## [1.1.0](https://github.com/michalsn/codeigniter-htmx/compare/v1.0.0...v1.1.0) - 2023-01-10

### Enhancements
- Add support for `htmx` and `boosted` param to the `IncomingRequest::is()` method.

## [1.0.0](https://github.com/michalsn/codeigniter-htmx/compare/v1.0.0-beta.2...v1.0.0) - 2022-12-09

### Enhancements
- When an HTTP error response occurs, display it in the modal.
- Add `view_fragment()` function to return only fragments of the view.

### Fixed
- Fixed the issue with applying an `ErrorDecorator` to the CLI requests.

## [1.0.0-beta.2](https://github.com/michalsn/codeigniter-htmx/compare/v1.0.0-beta...v1.0.0-beta.2) - 2022-11-22

### Changed
- Stop including code responsible for showing Debug Toolbar for `htmx` requests.

## 1.0.0-beta - 2022-11-19
First beta realease
