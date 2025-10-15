<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionDetail extends Model
{
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'id', 'distribution_id', 'product_id', 'qty', 'price', 'total', 'created_by'
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'float',
        'total' => 'float',
    ];

    public function distribution()
    {
        return $this->belongsTo(Distribution::class, 'distribution_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
