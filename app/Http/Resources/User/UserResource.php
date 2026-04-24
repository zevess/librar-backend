<?php

namespace App\Http\Resources\User;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isSameUser = $request->user() && $request->user()->is($this->resource);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'notifications' => $this->when($isSameUser, $this->unreadNotifications()->count()),
            'isVerified' => $this->hasVerifiedEmail(),
            'isDeleted' => (bool) $this->deleted_at
        ];
    }
}
