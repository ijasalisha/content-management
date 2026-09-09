<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'menu' => $this->whenLoaded('menu', function () {
                return [
                    'id' => $this->menu->id,
                    'title' => $this->menu->title,
                    'parent_id' => $this->menu->parent_id,
                    'sort_order' => $this->menu->sort_order,
                ];
            }),
            'title' => $this->title,
            'body' => $this->body,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'status' => $this->status,
            'publish_at' => $this->publish_at,
            'created_by' => $this->whenLoaded('creator', function () {
    return [
        'id' => $this->creator->id,
        'name' => $this->creator->name,
        'email' => $this->creator->email,
    ];
}),

'updated_by' => $this->whenLoaded('updater', function () {
    return [
        'id' => $this->updater->id,
        'name' => $this->updater->name,
        'email' => $this->updater->email,
    ];
}),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
