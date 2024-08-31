<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'client_id'];

    // Relationship with Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relationship with Kit
    public function kits()
    {
        return $this->hasMany(Kit::class);
    }

    // Relationship with Entry
    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    // Relationship with Ticket
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
