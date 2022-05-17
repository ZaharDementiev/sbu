<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int     $id
 * @property string  $title
 * @property string  $content
 * @property string  $votes
 */
class Petition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'votes'
    ];
}
