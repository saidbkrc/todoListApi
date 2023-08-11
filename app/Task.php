<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'status', 'user_id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
