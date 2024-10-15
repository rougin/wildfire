# Wildfire

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

Wildfire is a simple wrapper for the [Query Builder Class](https://codeigniter.com/user_guide/database/query_builder.html) based on [Codeigniter 3](https://codeigniter.com/userguide3/). It is inspired from the [Eloquent ORM](https://laravel.com/docs/5.6/eloquent) of Laravel.

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

Then configure the `composer_autoload` option in `config.php`:

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
$config['composer_autoload'] = __DIR__ . '/../../vendor/autoload.php';
```

> [!NOTE]
> Its value should be the path of the `vendor` directory.

Next is to extend the model (e.g., `User`) to the `Model` class of `Wildfire`:

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

### Using `Wildfire`

### With `CI_DB_query_builder`

After configuring the application, the `Wildfire` can now be used for returning results from the database as `Model` objects:

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

### With `CI_DB_result`

Aside from using methods of `Wildfire`, raw SQL queries can also be converted to its `Model` counterpart:

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

## Properties of `Model` class

The `Model` class provides the following properties that helps writing clean code and the said properties also conforms to the properties based on `Eloquent ORM`.

### Casting attributes

Updating the `$casts` property allows the model to cast native types to the specified attributes:

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

### Hiding attributes

To hide attributes for serialization, the `$hidden` property can be used:

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

In this example, the `gender` field was not included in the result.

### Visible attributes

Opposite of the `$hidden` property, the `$visible` property specifies the fields to be visible in the result:

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

From the example, only the `gender` field was displayed in the result because it was the only field specified in the `$visible` property of the `User` model.

### Using timestamps

Similar to `Eloquent ORM`, `Wildfire` enables the usage of timestamps by default:

``` php
// application/models/User.php

class User extends \Rougin\Wildfire\Model
{
    /**
     * Allows usage of timestamp fields ("CREATED_AT", "UPDATED_AT").
     *
     * @var boolean
     */
    protected $timestamps = true;
}
```

When enabled, it will use the constants `CREATED_AT` and `UPDATED_AT` for auto-populating them with current timestamps. To modify the names specified in the specified timestamps, kindly create the specified constants to the model (e.g., `User`):

``` php
// application/models/User.php

class User extends \Rougin\Wildfire\Model
{
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';
}
```

> [!NOTE]
> Auto-populating of timestamps in the specified constants is used in `WritableTrait`.

### Customizing attributes

When accessing an attribute through the model, `Wildfire` uses the same mechanism as from `Eloquent ORM` to return the requested attribute from the `$attributes` property. To customize the output of an attribute (e.g., converting an attribute to a date format), add a method inside the `Model` with a name format `get_[ATTRIBUTE]_attribute`:

``` php
// application/models/User.php

class User extends \Rougin\Wildfire\Model
{
    /**
     * @return string
     */
    public function get_created_at_attribute()
    {
        return date('d F Y H:i:sA', strtotime($this->attributes['created_at']));
    }
}
```

After creating a method for the specified attribute, the `Model` class will call the said method (e.g., `get_created_at_attribute`) if the specified attribute is accessed (e.g., `$user->created_at`).

## Using Traits

`Wildfire` provides traits that are based from the libraries of `Codeigniter 3` such as `Form Validation` and `Pagination Class`. They are used to easily attach the specified functionalities of `Codeigniter 3` to a model.

### `PaginateTrait`

The `PaginateTrait` is used to easily create pagination links within the model:

``` php
// application/models/User.php

use Rougin\Wildfire\Traits\PaginateTrait;

class User extends \Rougin\Wildfire\Model
{
    use PaginateTrait;

    // ...
}
```

``` php
// application/controllers/Welcome.php

// Create a pagination links with 10 as the limit and
// 100 as the total number of items from the result.
$result = $this->user->paginate(10, 100);

$data = array('links' => $result[1]);

$offset = $result[0];

// The offset can now be used for filter results
// from the specified table (e.g., "users").
$items = $this->user->get(10, $offset);
```

The `$result[0]` returns the computed offset while `$result[1]` returns the generated pagination links:

``` php
// application/views/users/index.php

<?php echo $links; ?>
```

To configure the pagination library, the `$pagee` property must be defined in the `Model`:

``` php
// application/models/User.php

use Rougin\Wildfire\Traits\PaginateTrait;

class User extends \Rougin\Wildfire\Model
{
    use PaginateTrait;

    // ...

    /**
     * Additional configuration to Pagination Class.
     *
     * @link https://codeigniter.com/userguide3/libraries/pagination.html#customizing-the-pagination
     *
     * @var array<string, mixed>
     */
    protected $pagee = array(
        'page_query_string' => true,
        'use_page_numbers' => true,
        'query_string_segment' => 'p',
        'reuse_query_string' => true,
    );
}
```

> [!NOTE]
> Please see the documentation of [Pagination Class](https://codeigniter.com/userguide3/libraries/pagination.html#customizing-the-pagination) to get the list of its available configuration.

### `ValidateTrait`

This trait is used to simplify the specifying of validation rules to a model:

``` php
// application/models/User.php

use Rougin\Wildfire\Traits\ValidateTrait;

class User extends \Rougin\Wildfire\Model
{
    use ValidateTrait;

    // ...
}
```

When used, the `$rules` property of the model must be defined with validation rules that conforms to the `Form Validation` specification:

``` php
// application/models/User.php

use Rougin\Wildfire\Traits\ValidateTrait;

class User extends \Rougin\Wildfire\Model
{
    use ValidateTrait;

    // ...

    /**
     * List of validation rules.
     *
     * @link https://codeigniter.com/userguide3/libraries/form_validation.html#setting-rules-using-an-array
     *
     * @var array<string, string>[]
     */
    protected $rules = array(
        array('field' => 'name', 'label' => 'Name', 'rules' => 'required'),
        array('field' => 'email', 'label' => 'Email', 'rules' => 'required'),
    );
}
```

> [!NOTE]
> Kindly check [its documentation](https://codeigniter.com/userguide3/libraries/form_validation.html#setting-rules-using-an-array) for the available rules that can be used to the `$rules` property.

To do a form validation, the `validate` method must be called from the model:

``` php
// application/controllers/Welcome.php

/** @var array<string, mixed> */
$input = $this->input->post(null, true);

$valid = $this->user->validate($input);
```

If executed with a view, the validation errors can be automatically be returned to the view using the `form_error` helper:

``` php
// application/views/users/create.php

<?= form_open('users/create') ?>
  <div>
    <!-- ... -->

    <?= form_error('name') ?>
  </div>

  <div>
    <!-- ... -->

    <?= form_error('email') ?>
  </div>

  <!-- ... -->
<?= form_close() ?>
```

### `WritableTrait`

The `WritableTrait` is a special trait that enables the model to perform CRUD operations:

``` php
// application/models/User.php

use Rougin\Wildfire\Traits\WritableTrait;

class User extends \Rougin\Wildfire\Model
{
    use WritableTrait;

    // ...
}
```

If added, the model can now perform actions such as `create`, `delete`, and `update`:

``` php
// application/controllers/Welcome.php

/** @var array<string, mixed> */
$input = $this->input->post(null, true);

// Create the user with the given input
$this->user->create($input);

// Delete the user based on its ID.
$this->user->delete($id);

// Update the user details with its input
$this->user->update($id, $input);
```

> [!NOTE]
> When using this trait, the `CREATED_AT` and `UPDATED_AT` constants will be populated if the `$timestamps` property of a model is enabled.

### `WildfireTrait`

Similar to `WritableTrait`, the `WildfireTrait` allows the model to use methods directly from the `Wildfire` class:

``` php
// application/models/User.php

use Rougin\Wildfire\Traits\WildfireTrait;

class User extends \Rougin\Wildfire\Model
{
    use WildfireTrait;

    // ...
}
```

Adding it to a model enables the methods such as `find` and `get` methods without specifying the database table:

``` php
// application/controllers/Welcome.php

/** @var array<string, mixed> */
$input = $this->input->post(null, true);

// Find the user based on the given ID
$item = $this->user->find($id);

// Return a filtered list of users based on
// the specified limit and its given offset
$items = $this->user->get($limit, $offset);
```

## Migrating to the `v0.5.0` release

The new release for `v0.5.0` will be having a [backward compatibility](https://en.wikipedia.org/wiki/Backward_compatibility) break (BC break). With this, some functionalities from the earlier versions might not be working after upgrading. This was done to increase the maintainability of the project while also adhering to the functionalities for both `Codeigniter 3` and `Eloquent ORM`. Please see the [UPGRADING][link-upgrading] page for the said breaking changes.

> [!TIP]
> If still using the `v0.4.0` release, kindly click its documentation below:
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