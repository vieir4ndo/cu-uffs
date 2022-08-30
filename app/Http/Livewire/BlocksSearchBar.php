<?php

namespace App\Http\Livewire;

use App\Models\Block;
use Livewire\Component;

class BlocksSearchBar extends Component
{
    public $blocks;

    public function mount()
    {
        $this->blocks = Block::where('status_block', 1)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.blocks-search-bar');
    }
}
