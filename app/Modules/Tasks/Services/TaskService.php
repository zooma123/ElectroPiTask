<?php

namespace App\Modules\Tasks\Services;

use App\Core\BaseService;
use App\Modules\Projects\Repositories\ProjectRepository;
use App\Modules\Tasks\Repositories\TaskRepository;
use Illuminate\Support\Facades\Auth;

class TaskService extends BaseService
{
    protected TaskRepository $taskRepository;
    protected ProjectRepository $projectRepository;

    public function __construct(TaskRepository $taskRepository, ProjectRepository $projectRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
    }

    public function listTasks(array $filters): self
    {
        $tasks = $this->taskRepository->getFilteredTasks(Auth::id(), $filters);
        return $this->setData($tasks);
    }

    public function createTask(array $data): self
    {
        // Check if the user owns the project
        $project = $this->projectRepository->findProjectForUser($data['project_id'], Auth::id());
        if (!$project) {
            return $this->setError('Project not found or unauthorized', 404);
        }

        $task = $this->taskRepository->create($data);
        return $this->setData($task);
    }

    public function updateTask(int $id, array $data): self
    {
        $task = $this->taskRepository->findTaskForUser($id, Auth::id());

        if (!$task) {
            return $this->setError('Task not found or unauthorized', 404);
        }

        if (isset($data['project_id'])) {
            $project = $this->projectRepository->findProjectForUser($data['project_id'], Auth::id());
            if (!$project) {
                return $this->setError('Project not found or unauthorized', 404);
            }
        }

        $this->taskRepository->update($task, $data);
        return $this->setData($task->fresh());
    }

    public function deleteTask(int $id): self
    {
        $task = $this->taskRepository->findTaskForUser($id, Auth::id());

        if (!$task) {
            return $this->setError('Task not found or unauthorized', 404);
        }

        $this->taskRepository->delete($task);
        return $this->setData(['message' => 'Task deleted successfully']);
    }
}
