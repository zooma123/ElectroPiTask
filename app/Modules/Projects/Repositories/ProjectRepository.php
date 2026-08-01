<?php

namespace App\Modules\Projects\Repositories;

use App\Modules\Projects\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectRepository
{
    protected Project $model;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function getProjectsByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        $allowedSorts = ['id', 'name', 'status', 'created_at', 'updated_at'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'created_at';
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        return $this->model->with('tasks')
            ->where('user_id', $userId)
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);
    }

    public function findProjectForUser(int $id, int $userId): ?Project
    {
        return $this->model->with('tasks')->where('id', $id)->where('user_id', $userId)->first();
    }

    public function create(array $data): Project
    {
        return $this->model->create($data);
    }

    public function update(Project $project, array $data): bool
    {
        return $project->update($data);
    }

    public function delete(Project $project): ?bool
    {
        return $project->delete();
    }
}
