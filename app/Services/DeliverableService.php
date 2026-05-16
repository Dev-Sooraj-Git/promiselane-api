<?php

namespace App\Services;

use App\Models\Deliverable;
use App\Models\Milestone;

class DeliverableService
{
    public function listByMilestone(Milestone $milestone)
    {
        return $milestone->deliverables()
            ->latest()->get();
    }

    public function create(Milestone $milestone, array $data)
    {
        return $milestone->deliverables()->create($data);
    }

    public function delete(Deliverable $deliverable)
    {
        return $deliverable->delete();
    }
}
