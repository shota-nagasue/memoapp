<?php

namespace App\Services\UseCases\Memos;
use App\Models\Memo;

class DeleteUseCase
{
    public function handle(int $id): void
    {
        $memo = Memo::findOrFail($id);
        $memo->delete();
    }
}
