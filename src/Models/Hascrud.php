<?php

namespace Sheenazien8\Hascrudactions\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request' => Json::class,
        'permission' => 'boolean',
        'server_side' => 'boolean',
    ];

    public function hascrudRows(): HasMany
    {
        return $this->hasMany(HascrudRow::class);
    }
}
