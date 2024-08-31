<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'site',
        'report_date',
        'data'
    ];

    // Assuming the report is related to the Site model
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
