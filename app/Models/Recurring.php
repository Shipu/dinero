<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shipu\Watchable\Traits\WatchableTrait;

class Recurring extends Model
{
    use HasFactory, WatchableTrait;
}
