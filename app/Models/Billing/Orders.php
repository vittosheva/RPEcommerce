<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_order',
        'invoice_number',
        'razon_social',
        'nombre_comercial',
        'ced_ruc',
        'firstname',
        'lastname',
        'fullname',
        'email',
        'address1',
        'address2',
        'postcode',
        'city',
        'phone',
        'phone_mobile',
        'total_paid_tax_incl',
        'total_paid_tax_excl',
        'total_discounts',
        'tax',
        'invoice_date',
        'created_at',
        'updated_at',
        'status'
    ];

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getTotalPaidTaxInclAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getTotalPaidTaxExclAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getTotalDiscountsAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getTaxAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }


    public function getLastOrder()
    {
        return $this
            ->select('id_order')
            ->orderBy('id_order', 'DESC')
            ->first();
    }

    public function getOrders($amount)
    {
        return $this
            ->select('*')
            ->orderBy('id_order', 'DESC')
            ->paginate($amount);
    }

    public function unprocessedOrders()
    {
        return $this
            ->with('orderDetails')
            ->select('*')
            ->where('status', '=', 0)
            ->orderBy('id_order', 'ASC')
            ->get();
    }

    public function orderDetails()
    {
        return $this
            ->hasMany(OrderDetail::class, 'id_order');
    }
}
