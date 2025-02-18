<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sharedTasks()
    {
        return $this->belongsToMany(User::class, 'user_tasks')->withPivot('permissions');
    }

}
