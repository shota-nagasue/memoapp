<?php

namespace App\Http\Controllers\Memos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Memos\MemoResource;
use App\Services\UseCases\Memos\ListUseCase;

class ListController extends Controller
{
    public function __construct(private ListUseCase $useCase) {}

    public function __invoke()
    {
        $memos = $this->useCase->handle();
        return MemoResource::collection($memos);
    }
}
