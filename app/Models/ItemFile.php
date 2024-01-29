<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'file_path',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
