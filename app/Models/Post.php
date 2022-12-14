<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'image','numTicket','dateGame'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function raffles(){
        return $this->hasMany(Raffle::class);
    }
}
