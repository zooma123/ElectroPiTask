<?php

namespace App\Modules\Tasks\Repositories;

use App\Modules\Tasks\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TaskRepository
{
    protected Task $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function getFilteredTasks(int $userId, array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $allowedSorts = ['id', 'title', 'priority', 'status', 'due_date', 'created_at', 'updated_at'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'created_at';
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        return $this->model->with('project')
        ->whereHas('project', function (Builder $query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->when(isset($filters['status']), function ($q) use ($filters) {
            return $q->where('status', $filters['status']);
        })
        ->when(isset($filters['priority']), function ($q) use ($filters) {
            return $q->where('priority', $filters['priority']);
        })
        ->when(isset($filters['title']), function ($q) use ($filters) {
            return $q->where('title', 'like', '%' . $filters['title'] . '%');
        })
        ->orderBy($sortBy, $sortOrder)
        ->paginate($perPage);
    }

    public function findTaskForUser(int $id, int $userId): ?Task
    {
        return $this->model->with('project')
            ->where('id', $id)
            ->whereHas('project', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();
    }

    public function create(array $data): Task
    {
        return $this->model->create($data);
    }

    public function update(Task $task, array $data): bool
    {
        return $task->update($data);
    }

    public function delete(Task $task): ?bool
    {
        return $task->delete();
    }
}
