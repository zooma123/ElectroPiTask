<?php

namespace App\Modules\Projects\Models;

use App\Modules\Auth\Models\User;
use App\Modules\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\ProjectFactory::new();
    }
}
