<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Petition;
use Illuminate\Database\Eloquent\Collection;

final class PetitionRepository
{
    public function getLimited(int $lastPetitionId): Collection
    {
        return Petition::query()
            ->where('id', '<', $lastPetitionId)
            ->get();
    }

    public function takeFirst(int $id): Petition
    {
        /* @var  $petition Petition*/
        $petition = Petition::query()
            ->find($id);
        return $petition;
    }

    public function increaseVotes(int $petitionId): int
    {
        return Petition::query()
            ->where('id', $petitionId)
            ->increment('votes');
    }
}
