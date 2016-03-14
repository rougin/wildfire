# Wildfire

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Yet another wrapper for [CodeIgniter](https://codeigniter.com)'s [Query Builder Class](https://codeigniter.com/user_guide/database/query_builder.html).

## Install

Via Composer

``` bash
$ composer require rougin/wildfire
```

## Usage

**models/User.php**

``` php
class User extends CI_Model {}
```

### Using [Query Builder](https://codeigniter.com/user_guide/database/query_builder.html)

**controllers/Welcome.php**

``` php
$this->load->model('user');

// Build your queries here
$this->db->like('name', 'John Doe', 'both');

// Instantiate Wildfire with the CI_DB class
$wildfire = new Rougin\Wildfire\Wildfire($this->db);

// Returns an array of User objects
$users = $wildfire->get('user')->result();
```

### Using raw SQL query

`controllers/Welcome.php`

``` php
$this->load->model('user');

$query = $this->db->query('SELECT * FROM users');

// Instantiate Wildfire with the database class and the query
$wildfire = new Rougin\Wildfire\Wildfire($this->db, $query);

// Returns an array of User objects
$users = $wildfire->result();
```

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [Rougin Royce Gutib][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/wildfire.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/wildfire/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/wildfire.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/wildfire.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/wildfire.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rougin/wildfire
[link-travis]: https://travis-ci.org/rougin/wildfire
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/wildfire/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/wildfire
[link-downloads]: https://packagist.org/packages/rougin/wildfire
[link-author]: https://github.com/rougin
[link-contributors]: ../../contributors
