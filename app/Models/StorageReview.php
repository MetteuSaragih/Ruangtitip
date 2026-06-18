<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageReview extends Model
{
    protected $fillable = ['storage_id','reviewer_name','rating','text','avatar','color'];
}
