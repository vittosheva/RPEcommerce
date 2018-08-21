<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class InvoiceProofCode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoice_proof_codes';

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
        'EDocTypeId',
        'EDocTypeCode',
        'description',
        'EDocTypeSri',
        'status'
    ];
}
