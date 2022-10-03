<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'user_id', 'annual_usage', 'common_name', 'name', 'description', 'balance', 'remark'];

    public function supplier()
    {
        return $this->hasMany(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
