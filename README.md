<div style="text-align:center"><img width="250px" src="https://i.ibb.co/vwN3vg2/Group-3.png" /></div>

# Hascrudaction

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sheenazien8/hascrudactions.svg?style=flat-square)](https://packagist.org/packages/sheenazien8/hascrudactions)
[![Build Status](https://img.shields.io/travis/sheenazien8/hascrudactions/master.svg?style=flat-square)](https://travis-ci.org/sheenazien8/hascrudactions)
[![Total Downloads](https://img.shields.io/packagist/dt/sheenazien8/hascrudactions.svg?style=flat-square)](https://packagist.org/packages/sheenazien8/hascrudactions)
<!--[![Quality Score](https://img.shields.io/scrutinizer/g/sheenazien8/hascrudactions.svg?style=flat-square)](https://scrutinizer-ci.com/g/sheenazien8/hascrudactions)-->

This package allows you to build a CRUD with tiny Controller and keep the pattern are consitent.

## Lifecycles
| Method       | Path                            | Function                    | Repository Function         | Route Name   |
| :----------- | :----------                     | :-----------                | :--------                   | :----------- |
| GET          | base_domain/path/               | ControllerClass@index       | RepositoryClass@datatable   | path.index   |
| GET          | base_domain/path/create         | ControllerClass@create      | -----                       | path.create  |
| GET          | base_domain/path/{id}           | ControllerClass@show        | -----                       | path.show    |
| POST         | base_domain/path/               | ControllerClass@store       | RepositoryClass@create      | path.store   |
| GET          | base_domain/path/{id}           | ControllerClass@edit        | -----                       | path.edit    |
| PUT          | base_domain/path/{id}           | ControllerClass@update      | RepositoryClass@update      | path.update  |
| DELETE       | base_domain/path/{id}           | ControllerClass@destroy     | RepositoryClass@delete      | path.destroy |
| DELETE       | base_domain/bulkDeletepath/path | ControllerClass@bulkDestroy | RepositoryClass@bulkDestroy | path.bulkDestroy |


## Requirements
* laravel 7.^
* This package uses ```yajra/laravel-datatables``` (https://yajrabox.com/docs/laravel-datatables/master) for index table view under the hood, Please make sure you include this dependencies before using this library.

## Installation

You can install the package via composer:

```bash
composer require sheenazien8/hascrudactions
```
## Configuration
#### Laravel Configuration
```bash
php artisan vendor:publish --provider="Sheenazien8\Hascrudactions\HascrudactionsServiceProvider"
```
#### Lumen Configuration
```bash
mkdir -p config
cp vendor/sheenazien8/hascrudactions/config/config.php config/config.php
```
## Basic Usage
First, Generate the Hascrud route with ```php artisan hascrudaction:make Employee``` this command will be generate a few class and file views, Example.
*   App\Http\Controllers\EmployeeController
*   App\Http\Requests\Employee\\.*
*   App\Repositories\EmployeeRepository
*   resources/views/employee/\.*

Next, you can add the path in ```routes/web.php```, edit file config in ```config/hascrudactions.php``` for wrapper blade layouts and javascript views, add Traits ```HasLaTable``` in Model Class Employee.
#### routes/web.php
```php
Route::hascrud('employee');
```
#### config/hascrudactions.php
```php
/**
 * Wrapper view, you can adjust static view like footer adn header
 * put file in views path
 * layouts:
 * section:
 * javascript:
 */
'wrapper' => [
    'layouts' => 'layouts.app',
    'section' => 'content',
    'javascript' => 'javascript'
]
```
#### app/Employee.php
```php
use Sheenazien8\Hascrudactions\Traits\HasLaTable;

class Employee extends Model
{
    use HasLaTable;
    //
}
```

And the last, you can define what columns you want to display in the index view in ```resources/views/employee/components/table.blade.php```, and then you must define what columns you want to add or edit in the model ``Employee`` with ```$fillable``` property  and form view ```resources/views/employee/components/form.blade.php```


### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Credits

- [Sheena Zien](https://github.com/sheenazien8)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
