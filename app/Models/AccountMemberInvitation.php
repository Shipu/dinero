<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountMemberInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'email',
    ];
}
