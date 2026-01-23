<?php

namespace App\Http\Controllers\Memos;

use App\Http\Controllers\Controller;
use App\Services\UseCases\Memos\ListUseCase;

class ListController extends Controller
{
    public function __construct(private ListUseCase $useCase) {}

    public function __invoke()
    {
        return response()->json($this->useCase->handle());
    }
}
