<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $projectModel = new Project();
        $serviceModel = new Service();
        $messageModel = new ContactMessage();
        $userModel = new User();

        $stats = [
            'projects_total'     => $projectModel->count(),
            'projects_published' => $projectModel->count('status = :s', ['s' => 1]),
            'services_active'    => $serviceModel->count('status = :s', ['s' => 1]),
            'messages_total'     => $messageModel->count(),
            'messages_unread'    => $messageModel->unreadCount(),
            'users_total'        => $userModel->count(),
        ];

        $recentMessages = $messageModel->latest(5);
        $recentProjects = $projectModel->paginatedAdmin();
        $recentProjects = array_slice($recentProjects, 0, 5);

        $this->adminView('admin/dashboard/index', [
            'title'          => 'Dashboard',
            'stats'          => $stats,
            'recentMessages' => $recentMessages,
            'recentProjects' => $recentProjects,
        ]);
    }
}
