<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_detail';

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
        'id_order_detail',
        'id_order',
        'id_order_invoice',
        'id_shop',
        'product_id',
        'product_attribute_id',
        'product_name',
        'product_quantity',
        'product_price',
        'product_quantity_discount',
        'product_reference',
        'unit_price_tax_excl',
        'total_price_tax_excl',
        'tax'
    ];

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getProductPriceAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getProductQuantityDiscountAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getUnitPriceTaxExclAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getTotalPriceTaxExclAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getTaxAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }


    public function getOrderDetailByIdOrder($id)
    {
        return $this
            ->select('*')
            ->where('id_order', '=', $id)
            ->orderBy('id_order', 'DESC')
            ->get();
    }

    public function getOrderDetailByIdOrderDetail($id)
    {
        return $this
            ->select('*')
            ->where('id_order_detail', '=', $id)
            ->orderBy('id_order_detail', 'DESC')
            ->get();
    }

    public function getLastOrderDetail()
    {
        return $this
            ->select('id_order_detail')
            ->orderBy('id_order_detail', 'DESC')
            ->first();
    }

    public function getOrderDetails($amount)
    {
        return $this
            ->select('*')
            ->orderBy('id', 'DESC')
            ->paginate($amount);
    }
}
