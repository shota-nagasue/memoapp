<?php

namespace App\Services\UseCases\Memos;

use App\Models\Memo;

class CreateUseCase
{
    public function handle(array $data): Memo
    {
        return Memo::create($data);
    }
}
