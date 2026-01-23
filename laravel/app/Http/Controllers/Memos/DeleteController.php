<?php

namespace App\Http\Controllers\Memos;

use App\Http\Controllers\Controller;
use App\Services\UseCases\Memos\DeleteUseCase;

class DeleteController extends Controller
{
    public function __construct(private DeleteUseCase $useCase) {}

    public function __invoke($id)
    {
        $this->useCase->handle($id);
        return response()->noContent();
    }
}
