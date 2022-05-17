<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Vote;

final class VoteRepository
{
    public function existsByUserAndPetition(int $userId, int $petitionId): bool
    {
        return Vote::query()
            ->where('user_id', $userId)
            ->where('petition_id', $petitionId)
            ->exists();
    }

    public function store(array $fields): Vote
    {
        /* @var $vote Vote */
        $vote = Vote::query()
            ->create($fields);

        return $vote;
    }
}
