<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'stock',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function itemFiles()
    {
        return $this->hasMany(ItemFile::class);
    }
}
