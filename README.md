# Wildfire

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Wildfire is a wrapper for [Query Builder Class](https://codeigniter.com/user_guide/database/query_builder.html) from the [Codeigniter](https://codeigniter.com) framework.

## Install

Via [Composer](https://getcomposer.org):

``` bash
$ composer require rougin/wildfire
```

## Usage

### Tables (in SQLite)

``` sql
CREATE TABLE "user" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" TEXT NOT NULL,
    "age" INTEGER NOT NULL,
    "gender" TEXT NOT NULL
);

CREATE TABLE post (
    id INTEGER PRIMARY KEY,
    subject TEXT NOT NULL,
    message TEXT NOT NULL,
    user_id INTEGER,
    description TEXT NULL,
    FOREIGN KEY(user_id) REFERENCES users(id)
);
```

### Models

**application/models/User.php**

``` php
class User extends CI_Model {}
```

**application/models/Post.php**

``` php
class Post extends CI_Model {}
```

### Using with a [Query Builder](https://codeigniter.com/user_guide/database/query_builder.html)

**application/controllers/Welcome.php**

``` php
use Rougin\Wildfire\Wildfire;

// Load the required models
$this->load->model('post');
$this->load->model('user');

// Build your queries here
$this->db->like('subject', 'Foo Bar', 'both');

// Pass the existing \CI_DB instance
$wildfire = new Wildfire($this->db);

// Returns an array of Post objects with a
// User object per Post object from result
$posts = $wildfire->get('post')->result();
```

### Using with a raw SQL query

**application/controllers/Welcome.php**

``` php
use Rougin\Wildfire\Wildfire;

// Load the required models
$this->load->model('post');
$this->load->model('user');

// Create raw SQL queries here...
$query = $this->db->query('SELECT * FROM post');

// Pass the database class with the raw query
$wildfire = new Wildfire($this->db, $query);

// Returns an array of Post objects with a
// User object per Post object from result
$posts = $wildfire->result();
```

### Using the `Rougin\Wildfire\CodeigniterModel` instance

**application/models/Post.php**

``` php
use Rougin\Wildfire\CodeigniterModel;

class Post extends CodeigniterModel {}
```

**application/controllers/Welcome.php**

``` php
// Loads the Post model...
$this->load->model('post');

// Returns an array of Post objects with a
// User object per Post object from result
$posts = $this->post->all();
```

### Methods

#### $wildfire->find($table, $delimiters = [])

``` php
$delimeters = array('name' => 'Test');

// Returns a post with an ID of 1.
$posts = $wildfire->find('post', 1);

// Returns a post from the provided delimiters.
$posts = $wildfire->find('post', $delimiters);
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

#### Using the `Rougin\Wildfire\CodeigniterModel`

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
    public $columns = array('id', 'subject', 'message');

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
    public $columns = array('id', 'subject', 'message');

    /**
     * Columns that will be hidden in the display.
     * If not set, it will hide a "password" column if it exists.
     *
     * @var array
     */
    protected $hidden = array();

}
```

### Traits (for `Rougin\Wildfire\CodeigniterModel`)

#### Available traits

* `PaginateTrait` - creates a result with pagination links, utilizes [Pagination Class](https://www.codeigniter.com/user_guide/libraries/pagination.html)
* `ValidateTrait` - validate input data, utilizes [Form Validation](https://www.codeigniter.com/user_guide/libraries/form_validation.html)

#### Example

``` php
class Post extends \Rougin\Wildfire\CodeigniterModel {

    use \Rougin\Wildfire\Traits\ValidateTrait;
    use \Rougin\Wildfire\Traits\PaginateTrait;

}
```

## Change Log

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [Rougin Royce Gutib][link-author]
- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/wildfire.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/wildfire/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/wildfire.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/wildfire.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/wildfire.svg?style=flat-square

[link-author]: https://github.com/rougin
[link-author]: https://rougin.github.io
[link-changelog]: https://github.com/rougin/wildfire/blob/master/CHANGELOG.md
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/wildfire
[link-contributors]: https://github.com/rougin/wildfire/contributors
[link-downloads]: https://packagist.org/packages/rougin/wildfire
[link-license]: https://github.com/rougin/wildfire/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/wildfire
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/wildfire/code-structure
[link-travis]: https://travis-ci.org/rougin/wildfire