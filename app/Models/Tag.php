<?php

namespace App\Models;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'info',
        'x_1',
        'y_1',
        'x_2',
        'y_2',
        'x_3',
        'y_3',
        'x_4',
        'y_4'
    ];

    public function image()
    {
        return $this->belongsTo(User::class);
    }
}
