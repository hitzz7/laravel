<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use Notifiable;
    protected $fillable = ['name', 'description','quantity','is_inventory'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

}
