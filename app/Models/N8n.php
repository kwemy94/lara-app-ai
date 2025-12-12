<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class N8n extends Model
{
    protected $table = 'n8n_documents';
    protected $fillable = [
        'port_depart',
        'port_destination',
    ];
}
