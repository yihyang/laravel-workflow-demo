<?php

namespace App;

use App\Models\Traits\HasWorkflowTrait;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasWorkflowTrait;

    protected $fillable = [
        'type',
        'data'
    ];

    protected $casts = [
        'data' => 'json'
    ];
}
