<?php

namespace App\Services\UseCases\Memos;

class CreateUseCase
{
    public function handle(array $input): array
    {
        // DBは次。いまは受け取った値をそのまま返す（仮）
        return [
            'id' => 1,
            'title' => $input['title'] ?? '',
            'content' => $input['content'] ?? '',
        ];
    }
}
