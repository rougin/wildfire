# Wildfire

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

Wildfire is a package which is a simple wrapper for the [Query Builder Class](https://codeigniter.com/user_guide/database/query_builder.html) based from [Codeigniter 3](https://codeigniter.com/userguide3/). The package was inspired from the [Eloquent ORM](https://laravel.com/docs/5.6/eloquent) of Laravel.

## Installation

Install `Wildfire` via [Composer](https://getcomposer.org/):

``` bash
$ composer require rougin/wildfire
```

## Basic Usage

### Prerequisites

Create a sample database table to be used (e.g., `users`):

``` sql
-- Import this script to a SQLite database

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name TEXT NOT NULL,
    age INTEGER NOT NULL,
    gender TEXT NOT NULL,
    accepted INTEGER DEFAULT 0
);

INSERT INTO users (name, age, gender) VALUES ('Rougin', 20, 'male');
INSERT INTO users (name, age, gender) VALUES ('Royce', 18, 'male');
INSERT INTO users (name, age, gender) VALUES ('Mei', 19, 'female');
```

Then enable the `composer_autoload` option in `config.php`:

``` php
// application/config/config.php

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|   $config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|   $config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|   autoloading (application/config/autoload.php)
*/
$config['composer_autoload'] = TRUE;
```

Extend the specified model (e.g., `User`) to the `Model` class of `Wildfire`:

``` php
// application/models/User.php
use Rougin\Wildfire\Model;

class User extends Model
{
}
```

``` php
// application/controllers/Welcome.php

// Loads the database connection 
$this->load->database();

// Enables the inflector helper. It is being used to determine the class or the
// model name to use based from the given table name from the Wildfire.
$this->load->helper('inflector');

// Loads the required model/s
$this->load->model('user');
```

### Using the `Wildfire` instance with `CI_DB_query_builder`

``` php
// application/controllers/Welcome.php

use Rougin\Wildfire\Wildfire;

// Pass the \CI_DB_query_builder instance
$wildfire = new Wildfire($this->db);

// Can also be called to \CI_DB_query_builder
$wildfire->like('name', 'Royce', 'both');

// Returns an array of User objects
$users = $wildfire->get('users')->result();
```

### Using the `Wildfire` instance with `CI_DB_result`

``` php
// application/controllers/Welcome.php

use Rougin\Wildfire\Wildfire;

$query = 'SELECT p.* FROM post p';

// Create raw SQL queries here...
$result = $this->db->query($query);

// ...or even the result of $this->db->get()
$result = $this->db->get('users');

// Pass the result as the argument
$wildfire = new Wildfire($result);

// Returns an array of User objects
$users = $wildfire->result('User');
```

### Properties for the `Model` instance

#### Casting attributes to native types

``` php
// application/models/User.php

class User extends \Rougin\Wildfire\Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = array('accepted' => 'boolean');
}
```

**Without native casts**

``` json
{
    "id": 1,
    "name": "Rougin",
    "age": "20",
    "gender": "male",
    "accepted": "0",
}
```

**With native casts**

``` json
{
    "id": 1,
    "name": "Rougin",
    "age": "20",
    "gender": "male",
    "accepted": false,
}
```

Notice that the value of `accepted` was changed from string integer (`'0'`) into native boolean (`false`). If not specified (e.g. `age` field), all values will be returned as string except the `id` field (which will be automatically casted as native integer, also if the said column exists) by default.

#### Hiding attributes for serialization

``` php
// application/models/User.php

class User extends \Rougin\Wildfire\Model
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = array('gender');
}
```

**Without hidden attributes**

``` json
{
    "id": 1,
    "name": "Rougin",
    "age": "20",
    "gender": "male",
    "accepted": "0",
}
```

**With hidden attributes**

``` json
{
    "id": 1,
    "name": "Rougin",
    "age": "20",
    "accepted": "0",
}
```

The `gender` field was not included in the result.

#### Visible attributes for serialization

``` php
// application/models/User.php

class User extends \Rougin\Wildfire\Model
{
    /**
     * The attributes that should be visible for serialization.
     *
     * @var array
     */
    protected $visible = array('gender');
}
```

**Without visible attributes**

``` json
{
    "id": 1,
    "name": "Rougin",
    "age": "20",
    "gender": "male",
    "accepted": "0",
}
```

**With visible attributes**

``` json
{
    "gender": "male"
}
```

In contrast to the `hidden` attribute, only the `gender` field was displayed in the result because it was the only field specified the in `visible` property of the `User` model.

## Migrating to the `v0.5.0` release

The new release for `v0.5.0` will be having a [backward compatibility](https://en.wikipedia.org/wiki/Backward_compatibility) break (BC break). With this, some functionalities from the earlier versions might not be working after upgrading. This was done to increase the maintainability of the project while also adhering to the functionalities for both `Codeigniter 3` and `Eloquent ORM`. Please see the [UPGRADING][link-upgrading] page for the said breaking changes.

> [!TIP]
> If still using the `v0.4` release, kindly click its documentation below:
> https://github.com/rougin/credo/blob/v0.4.0/README.md

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/rougin/wildfire/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/rougin/wildfire?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/wildfire.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/rougin/wildfire.svg?style=flat-square

[link-build]: https://github.com/rougin/wildfire/actions
[link-changelog]: https://github.com/rougin/wildfire/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/rougin/wildfire/contributors
[link-coverage]: https://app.codecov.io/gh/rougin/wildfire
[link-downloads]: https://packagist.org/packages/rougin/wildfire
[link-license]: https://github.com/rougin/wildfire/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/wildfire
[link-upgrading]: https://github.com/rougin/wildfire/blob/master/UPGRADING.md