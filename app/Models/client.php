<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relationship with Site
    public function sites()
    {
        return $this->hasMany(Site::class);
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
