<?php

namespace App\Modules\Dashboard\Services;

use App\Core\BaseService;
use App\Modules\Projects\Models\Project;
use App\Modules\Tasks\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardService extends BaseService
{
    public function getDashboardStats(): self
    {
        $userId = Auth::id();

        $totalProjects = Project::where('user_id', $userId)->count();
        $activeProjects = Project::where('user_id', $userId)->where('status', 'Active')->count();

        // Get all task IDs belonging to the user's projects
        $projectIds = Project::where('user_id', $userId)->pluck('id');
        
        $tasksQuery = Task::whereIn('project_id', $projectIds);
        
        $totalTasks = (clone $tasksQuery)->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'Done')->count();
        $pendingTasks = (clone $tasksQuery)->whereIn('status', ['Todo', 'In Progress'])->count();
        $overdueTasks = (clone $tasksQuery)
            ->whereIn('status', ['Todo', 'In Progress'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->count();

        return $this->setData([
            'total_projects' => $totalProjects,
            'active_projects' => $activeProjects,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $pendingTasks,
            'overdue_tasks' => $overdueTasks,
        ]);
    }
}
