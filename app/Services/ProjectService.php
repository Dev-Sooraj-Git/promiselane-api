<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Str;


class ProjectService
{
    /**
     * Create a new class instance.
     */
    public function List(int $userId, string $status = null)
    {
        return Project::Where('user_id', $userId)
            ->When($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->get();
    }

    public function create(array $data){


    }
}
