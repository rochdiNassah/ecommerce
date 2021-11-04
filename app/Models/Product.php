<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path'
    ];

    /**
     * Get the orders associated with the product.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
