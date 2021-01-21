<?php

namespace Sheenazien8\Hascrudactions\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Hascrud
 * @author sheenazien8
 */
class Hascrud extends Model
{
    /**
     * @var array fillable collumn
     */
    protected $fillable = [
        'slug',
        'show_plural_name',
        'show_singular_name',
        'controller',
        'request',
        'permission',
        'server_side'
    ];
}
