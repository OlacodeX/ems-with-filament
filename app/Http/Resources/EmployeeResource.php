<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'first name' => $this->first_name,
            'last name' => $this->last_name,
            'birth date' => $this->birth_date,
            'date hired' => $this->date_hired,
            'zip code' => $this->zip_code,
            'address' => $this->address,
            'department' => [
                'id' => $this->department->id,
                'name' => $this->department->name,
            ],
            'country' => [
                'id' => $this->country->id,
                'name' => $this->country->name,
            ],
            'state' => [
                'id' => $this->state->id,
                'name' => $this->state->name,
            ],
            'city' => [
                'id' => $this->city->id,
                'name' => $this->city->name,
            ],
        ];
    }
}
