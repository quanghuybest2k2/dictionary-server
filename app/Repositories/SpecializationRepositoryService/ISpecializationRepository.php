<?php

namespace App\Repositories\SpecializationRepositoryService;

interface ISpecializationRepository
{
    public function getAll();
    public function find($id);
    public function getBySpecializationId($specializationId);
}
