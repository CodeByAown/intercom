<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'client_id',
        'site_id',
        'kit_id',
        'ticket_number',
        'location',
        'wan',
        'reason',
        'status'
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
