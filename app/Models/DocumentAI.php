<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAI extends Model
{
    protected $table = 'document_a_i_s';
    protected $fillable = [
        'port_embarquement',
        'port_destination',
    ];
}
