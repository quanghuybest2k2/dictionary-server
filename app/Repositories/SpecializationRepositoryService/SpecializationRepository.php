<?php

namespace App\Repositories\SpecializationRepositoryService;

use App\Models\Specialization;

class SpecializationRepository implements ISpecializationRepository
{
    public function find($id)
    {
        return Specialization::find($id);
    }
}
