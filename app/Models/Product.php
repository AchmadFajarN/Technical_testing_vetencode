<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        "id", "name", "price", "active"
    ];

    protected $cast = [
        "active" => "boolean",
        "price" => "decimal:10,2",
    ];

    public function distributionDetails() 
    {
        return $this -> hasMany(DistributionDetail::class, "product_id", 'id');
    }

}
