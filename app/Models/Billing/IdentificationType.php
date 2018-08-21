<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class IdentificationType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'identification_type';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'code',
        'requirement'
    ];
}
