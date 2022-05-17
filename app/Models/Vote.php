<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int  $id
 * @property int  $user_id
 * @property int  $petition_id
 */
final class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'petition_id'
    ];
}
