<?php

namespace App\Services\UseCases\Memos;

use App\Models\Memo;
use Illuminate\Database\Eloquent\Collection;

class ListUseCase
{
    /**
     * @return Collection<int, Memo>
     */
    public function handle(): Collection
    {
        return Memo::query()
            ->orderByDesc('created_at')
            ->get();
    }
}
