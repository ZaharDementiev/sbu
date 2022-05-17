<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Petition;
use App\Repositories\PetitionRepository;
use Illuminate\Database\Eloquent\Collection;

final class PetitionService
{
    public function __construct(private PetitionRepository $petitionRepository)
    {
    }

    public function getLimited(int $lastPetitionId): Collection
    {
        return $this->petitionRepository->getLimited($lastPetitionId);
    }

    public function takeFirst(int $id): Petition
    {
        return $this->petitionRepository->takeFirst($id);
    }
}
