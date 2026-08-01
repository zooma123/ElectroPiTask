<?php

namespace App\Modules\Tasks\Models;

use App\Modules\Projects\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\TaskFactory::new();
    }
}
