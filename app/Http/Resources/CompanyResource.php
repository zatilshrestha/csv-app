<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'is_duplicate' => $this->is_duplicate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
