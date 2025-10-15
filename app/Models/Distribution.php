<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribution extends Model
{
    use SoftDeletes;
    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        "id", "barista_id", "total_qty", "estimated_result", "notes", "created_by"
    ];

    protected $casts = [
        "total_qty" => "integer",
        "estimated_result" => "float"
    ];

    public function barista() 
    {
        return $this->belongsTo(User::class, 'barista_id', "id");
    }

    public function creator() 
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function details() 
    {
        return $this->hasMany(DistributionDetail::class, "distribution_id", "id");
    }
}
