<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Member extends Pivot
{
    protected $table = 'account_member';

    protected $fillable = [
        'account_id',
        'user_id',
    ];
}
