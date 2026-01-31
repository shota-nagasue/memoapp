<?php

namespace App\Http\Controllers\Memos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Memos\MemoResource;
use App\Services\UseCases\Memos\CreateUseCase;
use App\Http\Requests\Memos\CreateRequest;

class CreateController extends Controller
{
    public function __construct(private CreateUseCase $useCase) {}

    public function __invoke(CreateRequest $request)
    {
        $memo=$this->useCase->handle($request->validated());
        return new MemoResource($memo);

    }
}
