<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class ResponsableSearchBar extends Component
{
    public $responsables;

    public function mount()
    {
        $this->responsables = User::where('status_enrollment_id', 1)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.responsable-search-bar');
    }
}
