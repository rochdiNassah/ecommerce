<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['product', 'customer_details', 'token'];
    protected $guarded = [
        'status',
        'dispatcher',
        'delivery_driver'
    ];

    /**
     * Get the product associated with the order.
     */
    public function _product()
    {
        return $this->belongsTo(Product::class, 'product');
    }
}
