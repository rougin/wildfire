# Changelog

All notable changes to `Wildfire` will be documented in this file.

## [0.4.0](https://github.com/rougin/wildfire/compare/v0.3.1...v0.4.0) - CURRENT_DATE

### Added
- `CodeigniterModel` that extends to `CI_Model`
- `$belongs_to` for one-to-one relationships
- `$hidden` for hiding columns
- `ValidateTrait` for validating data using CodeIgniter's `Form_validation` class

## [0.3.1](https://github.com/rougin/wildfire/compare/v0.3.0...v0.3.1) - 2016-09-12

### Added
- `Wildfire` as `CI_Model`
- StyleCI for conforming code to PSR standards

## [0.3.0](https://github.com/rougin/wildfire/compare/v0.2.1...v0.3.0) - 2016-06-05

### Added
- Traits
- `table` for manually specifying the table associated with the Model
- `columns` for manually specifying the columns to be displayed

### Fixed
- Issue in getting foreign tables

### Removed
- `Inflector` class

## [0.2.1](https://github.com/rougin/wildfire/compare/v0.2.0...v0.2.1) - 2016-05-14

### Changed
- Version of `rougin/codeigniter` to `^3.0.0`

## [0.2.0](https://github.com/rougin/wildfire/compare/v0.1.0...v0.2.0) - 2016-03-25

### Changed
- Tests
- Enabled empty parameters in `Wildfire::__construct`

## 0.1.0 - 2016-03-06

### Added
- `Wildfire` library
