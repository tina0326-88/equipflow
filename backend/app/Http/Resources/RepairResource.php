<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'status'       => $this->status,
            'priority'     => $this->priority,
            'reported_by'  => $this->reported_by,
            'assigned_to'  => $this->assigned_to,
            'reported_at'  => $this->reported_at,
            'completed_at' => $this->completed_at,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'equipment'    => $this->whenLoaded('equipment', fn () => [
                'id'       => $this->equipment->id,
                'name'     => $this->equipment->name,
                'type'     => $this->equipment->type,
                'location' => $this->equipment->location,
                'status'   => $this->equipment->status,
            ]),
            'reporter' => $this->whenLoaded('reporter', fn () => $this->reporter ? [
                'id'         => $this->reporter->id,
                'name'       => $this->reporter->name,
                'department' => $this->reporter->department,
            ] : null),
            'assignee' => $this->whenLoaded('assignee', fn () => $this->assignee ? [
                'id'   => $this->assignee->id,
                'name' => $this->assignee->name,
            ] : null),
        ];
    }
}
