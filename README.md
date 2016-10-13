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

### Tables (in SQLite)

#### Users table

``` sql
CREATE TABLE "user" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" TEXT NOT NULL,
    "age" INTEGER NOT NULL,
    "gender" TEXT NOT NULL
);
```

#### application/models/User.php

``` php
class User extends CI_Model {}
```

#### Posts table

``` sql
CREATE TABLE post (
    id INTEGER PRIMARY KEY,
    subject TEXT NOT NULL,
    message TEXT NOT NULL,
    user_id INTEGER,
    description TEXT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id)
);
```

#### application/models/Post.php

``` php
class Post extends CI_Model {}
```

### Using [Query Builder](https://codeigniter.com/user_guide/database/query_builder.html)

#### application/controllers/Welcome.php

``` php
$this->load->model('post')->model('user');

// Build your queries here
$this->db->like('subject', 'Foo Bar', 'both');

// Instantiate Wildfire with the CI_DB class
$wildfire = new Rougin\Wildfire\Wildfire($this->db);

// Returns an array of Post objects with a User object per Post object
$posts = $wildfire->get('post')->result();
```

### Using raw SQL query

#### application/controllers/Welcome.php

``` php
$this->load->model('post')->model('user');

$query = $this->db->query('SELECT * FROM post');

// Instantiate Wildfire with the database class and the query
$wildfire = new Rougin\Wildfire\Wildfire($this->db, $query);

// Returns an array of Post objects with a User object per Post object
$posts = $wildfire->result();
```

### Using `Rougin\Wildfire\CodeigniterModel`

#### application/controllers/Welcome.php

``` php
$this->load->model('post');

// Returns an array of Post objects with a User object per Post object
$posts = $this->post->all();
```

### Methods

#### $wildfire->find($table, $delimiters = [])

``` php
// Returns a post with an ID of 1.
$posts = $wildfire->find('post', 1);

// Returns a post from the provided delimiters.
$posts = $wildfire->find('post', [ 'name' => 'Foo Bar' ]);
```

#### $wildfire->get($table = '')->as_dropdown($description = 'description')

``` php
// Returns a list of posts that can be used in form_dropdown().
// $description means what column will be used to display.
$posts = $wildfire->get('post')->as_dropdown('subject');
```

#### $wildfire->set_database($this->db)

``` php
// Sets as the current database
$wildfire->set_database($this->db);
```

#### $wildfire->set_query()

``` php
// Sets as the current query
$wildfire->set_query('SELECT * FROM posts');
```

### Using `Rougin\Wildfire\CodeigniterModel`

#### $this->model->find($id)

``` php
// Returns a post with an ID of 1.
$posts = $this->post->find(1);
```

#### $this->model->find_by(array $delimiters = [])

``` php
// Returns a post from the provided delimiters.
$posts = $this->post->find_by([ 'name' => 'Foo Bar' ]);
```

#### $this->model->get()->as_dropdown($description = 'description')

``` php
// Returns a list of posts that can be used in form_dropdown().
// $description means what column will be used to display.
$posts = $this->post->get()->as_dropdown('subject');
```

### Model Conventions

#### Extends from `CI_Model` (deprecated as of `v0.4.0`)

``` php
class Post extends CI_Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'post';

    /**
     * Columns that will be displayed.
     * If not set, it will get the columns from the database table.
     *
     * @var array
     */
    public $columns = array(
        'id',
        'subject',
        'message',
        'user_id',
        'description',
    );

}
```

#### Extends from `Rougin\Wildfire\CodeigniterModel`

``` php
class Post extends \Rougin\Wildfire\CodeigniterModel {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post';

    /**
     * Columns that will be displayed.
     * If not set, it will get the columns from the database table.
     *
     * @var array
     */
    protected $columns = array(
        'id',
        'subject',
        'message',
        'user_id',
        'description',
    );

    /**
     * Columns that will be hidden in the display.
     * If not set, it will hide a "password" column if it exists.
     *
     * @var array
     */
    protected $hidden = array('datetime_created', 'datetime_updated');

}
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
