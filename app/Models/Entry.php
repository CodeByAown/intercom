<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'client_id',
        'site_id',
        'kit_id',
        'speed',
        'poor_cable',
        'update_pending',
        'obstruction',
        'login_issue'
    ];

    // Relationship with Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relationship with Site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    // Relationship with Kit
    public function kit()
    {
        return $this->belongsTo(Kit::class);
    }
}
