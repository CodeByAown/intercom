<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kit extends Model
{
    use HasFactory;

    protected $fillable = ['kit_number', 'site_id', 'speed', 'poor_cable', 'update_pending', 'obstruction', 'login_issue'];

    // Relationship with Site
    public function site()
    {
        return $this->belongsTo(Site::class);
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
