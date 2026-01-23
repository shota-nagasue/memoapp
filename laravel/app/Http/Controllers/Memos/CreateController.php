<?php

namespace App\Http\Controllers\Memos;

use App\Http\Controllers\Controller;
use App\Services\UseCases\Memos\CreateUseCase;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __construct(private CreateUseCase $useCase) {}

    public function __invoke(Request $request)
    {
        $input = $request->only(['title', 'content']);
        $created = $this->useCase->handle($input);

        return response()->json($created, 201);
    }
}
