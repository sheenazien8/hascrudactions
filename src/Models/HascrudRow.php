<?php

namespace Sheenazien8\Hascrudactions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'collumn',
        'display_name',
        'store_validation',
        'update_validation',
        'is_multiple',
        'is_required',
        'show_in_read',
        'show_in_edit',
        'show_in_create',
        'show_in_detail'
    ];

    public function hascrud(): BelongsTo
    {
        return $this->belongsTo(Hascrud::class, 'hascrud_id');
    }
}
