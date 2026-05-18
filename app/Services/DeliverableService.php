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
        $deliverable = $milestone->deliverables()->create($data);

        app(TimelineService::class)->log(
            $milestone->project,
            'deliverable_uploaded',
            "File '{$deliverable->file_name}' uploaded to milestone '{$milestone->title}'"
        );

        return $deliverable;
    }

    public function delete(Deliverable $deliverable)
    {
        app(TimelineService::class)->log(
            $deliverable->milestone->project,
            'deliverable_deleted',
            "File '{$deliverable->file_name}' deleted from milestone '{$deliverable->milestone->title}'"
        );
        return $deliverable->delete();
    }
}
