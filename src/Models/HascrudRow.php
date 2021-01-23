<?php

namespace Sheenazien8\Hascrudactions\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Hascrud
 * @author sheenazien8
 */
class HascrudRow extends Model
{
    /**
     * @var array fillable collumn
     */
    protected $fillable = [
        'type',
        'display_name',
        'store_validation',
        'update_validation',
        'is_multiple',
        'is_required',
        'show_in_read',
        'show_in_edit',
        'show_in_create'
    ];
}
