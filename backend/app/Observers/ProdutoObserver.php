<?php

namespace App\Observers;

use App\Models\Produto;
use Illuminate\Support\Facades\Cache;

class ProdutoObserver
{
    private function clearCache(): void
    {
        Cache::forget('produtos_para_select');
    }

    /**
     * Handle the Produto "created" event.
     */
    public function created(Produto $produto): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Produto "updated" event.
     */
    public function updated(Produto $produto): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Produto "deleted" event.
     */
    public function deleted(Produto $produto): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Produto "restored" event.
     */
    public function restored(Produto $produto): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Produto "force deleted" event.
     */
    public function forceDeleted(Produto $produto): void
    {
        $this->clearCache();
    }
}
