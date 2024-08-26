The following are the breaking changes introduced in `v0.5`. As previously mentioned, this was done to improve its maintainability and to conform more on the methods from both the `Codeigniter 3` and `Eloquent ORM` projects:

### Change the `CodeigniterModel` class to `Model` class

**Before**

``` php
class User extends \Rougin\Wildfire\CodeigniterModel
{
}
```

**After**

``` php
class User extends \Rougin\Wildfire\Model
{
}
```

When `Wildfire` is used as a `CI_Model`, use `WildfireTrait` instead.

**Before**

``` php
class User extends \Rougin\Wildfire\Wildfire
{
}
```

**After**

``` php
class User extends \Rougin\Wildfire\Model
{
    use \Rougin\Wildfire\Traits\WildfireTrait;
}
```

### Change the arguments for `PaginateTrait::paginate`

**Before**

``` php
// PaginateTrait::paginate($perPage, $config = array())
list($result, $links) = $this->user->paginate(5, $config);
```

**After**

``` php
$total = $this->db->count_all_results('users');

// PaginateTrait::paginate($perPage, $total, $config = array())
list($offset, $links) = $this->user->paginate(5, $total, $config);
```

The total count must be passed in the second parameter.

### Remove `Model::countAll`

**Before**

``` php
$total = $this->user->countAll();
```

**After**

``` php
$total = $this->db->count_all_results('users');
```

This is being used only in `PaginateTrait::paginate`.

### Change the method `ValidateTrait::validation_errors` to `ValidateTrait::errors`

**Before**

``` php
ValidateTrait::validation_errors()
```

**After**

``` php
ValidateTrait::errors()
```

### Change the property `ValidateTrait::validation_rules` to `ValidateTrait::rules`

**Before**

``` php
// application/models/User.php

protected $validation_rules = array();
```

**After**

``` php
// application/models/User.php

protected $rules = array();
```

### Change the arguments for `Wildfire::__construct`

**Before**

``` php
$query = $this->db->query('SELECT * FROM users');

// Wildfire::__construct($database = null, $query = null)
$wildfire = new Wildfire($this->db, $query);
```

**After**

``` php
// $this->db->query returns a CI_DB_result class
$query = $this->db->query('SELECT * FROM users');

// Wildfire::__construct($data)
$wildfire = new Wildfire($query);
```

If the data is a `CI_DB_result`, it should be passed on the first parameter.

### Change the method `Wildfire::asDropdown` to `Wildfire::dropdown`

**Before**

``` php
// Wildfire::asDropdown($description = 'description')
$dropdown = $wildfire->asDropdown();
```

**After**

``` php
// Wildfire::dropdown($column)
$dropdown = $wildfire->dropdown('description');
```

Also take note that there is no default value in the argument.

### Replace `$delimiters` with `$id` in `Wildfire::find`

**Before**

``` php
$delimiters = array('name' => 'Rougin');

// Wildfire::find($table, $delimiters = array())
$users = $wildfire->find('users', $delimiters);
```

**After**

``` php
$this->db->where('name', (string) 'Rougin');

$users = $wildfire->get('users')->result();
```

Use only `Wildfire::find` to return single row data.

``` php
// Wildfire::find($table, $id)
$user = $wildfire->find('users', 1);
```

### Remove `set_database` and `set_query` methods

**Before**

``` php
use Rougin\Wildfire\Wildfire;

$wildfire = new Wildfire;

$wildfire->set_database($this->db);

$query = $this->db->query('SELECT * FROM users');

$wildfire->set_query($query);
```

**After**

``` php
use Rougin\Wildfire\Wildfire;

$wildfire = new Wildfire($this->db);

// or

$query = $this->db->query('SELECT * FROM users');

$wildfire = new Wildfire($query);
```

The `Wildfire` parameter must be defined with either `CI_DB_query_builder` (`$this->db`) or `CB_DB_result` instances.