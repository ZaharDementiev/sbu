<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\PetitionService;
use Illuminate\Contracts\View\View;

final class PetitionController extends Controller
{
    const PAGINATE_LIMIT = 6;

    public function __construct(private PetitionService $petitionService)
    {
    }

    public function index(): View
    {
        $petitions = $this->petitionService->getLimited(request()->input('last_petition_id') ?? self::PAGINATE_LIMIT);

        return view('index', compact('petitions'));
    }

    public function show(int $id): JsonResponse
    {
        return responseSuccess([
            $this->petitionService->takeFirst($id)
        ]);
    }

    public function vote()
    {

    }
}

