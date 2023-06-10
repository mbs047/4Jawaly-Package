<?php

namespace Devhereco\4Jawaly\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsHistory extends Model
{
    use HasFactory;
    const UPDATED_AT = null;

    protected $fillable = [
        'provider',
        'phone',
        'sender',
        'message',
        'status'
    ];
}
