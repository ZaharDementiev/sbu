<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\VoteService;
use Illuminate\Http\JsonResponse;

final class VoteController extends Controller
{
    public function __construct(private VoteService $voteService)
    {
    }

    public function __invoke(int $id): JsonResponse
    {
        return responseSuccess([
            $this->voteService->makeVote(auth()->id(), $id)
        ]);
    }
}
