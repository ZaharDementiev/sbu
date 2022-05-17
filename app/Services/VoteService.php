<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Vote;
use App\Repositories\PetitionRepository;
use App\Repositories\VoteRepository;
use Illuminate\Support\Facades\DB;

final class VoteService
{
    public function __construct(
        private VoteRepository $voteRepository,
        private PetitionRepository $petitionRepository
    ) {
    }

    public function makeVote(int $userId, int $petitionId): ?Vote
    {
        if ($this->voteRepository->existsByUserAndPetition($userId, $petitionId)) {
            return null;
        }

        DB::beginTransaction();

        $vote = $this->voteRepository->store(['user_id' => $userId, 'petition_id' => $petitionId]);
        $this->petitionRepository->increaseVotes($vote->petition_id);

        DB::commit();

        return $vote;
    }
}
