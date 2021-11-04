<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'customer', 'token'];
    protected $guarded = [
        'status',
        'dispatcher_id',
        'delivery_driver_id'
    ];

    /**
     * Get the product associated with the order.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order's dispatcher.
     */
    public function dispatcher()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the order's delivery driver.
     */
    public function deliveryDriver()
    {
        return $this->belongsTo(Member::class);
    }
}
