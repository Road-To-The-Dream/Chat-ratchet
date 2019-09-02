<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public static function random()
    {
        return Color::inRandomOrder()->first();
    }
}
