<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Image extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'title',
        'url',
        'height',
        'width',
        'category',
        'public',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function getPathWithUid()
    {
        if ($this->public) {
            return $this->path;
        }
        return $this->path . '?uid=' . urlencode($this->createToken('image_uid', [$this->path])->plainTextToken);
    }
}
