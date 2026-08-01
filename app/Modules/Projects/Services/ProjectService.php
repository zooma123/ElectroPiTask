<?php

namespace App\Modules\Projects\Services;

use App\Core\BaseService;
use App\Modules\Projects\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Auth;

class ProjectService extends BaseService
{
    protected ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function listUserProjects(array $filters = []): self
    {
        $projects = $this->projectRepository->getProjectsByUser(Auth::id(), $filters);
        return $this->setData($projects);
    }

    public function createProject(array $data): self
    {
        $data['user_id'] = Auth::id();
        $project = $this->projectRepository->create($data);
        return $this->setData($project);
    }

    public function viewProject(int $id): self
    {
        $project = $this->projectRepository->findProjectForUser($id, Auth::id());

        if (!$project) {
            return $this->setError('Project not found or unauthorized', 404);
        }

        return $this->setData($project);
    }

    public function updateProject(int $id, array $data): self
    {
        $project = $this->projectRepository->findProjectForUser($id, Auth::id());

        if (!$project) {
            return $this->setError('Project not found or unauthorized', 404);
        }

        $this->projectRepository->update($project, $data);
        return $this->setData($project->fresh());
    }

    public function deleteProject(int $id): self
    {
        $project = $this->projectRepository->findProjectForUser($id, Auth::id());

        if (!$project) {
            return $this->setError('Project not found or unauthorized', 404);
        }

        $this->projectRepository->delete($project);
        return $this->setData(['message' => 'Project deleted successfully']);
    }
}
