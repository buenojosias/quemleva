<?php

namespace App\Livewire\Campaign;

use App\Models\Campaign;
use Livewire\Component;

class Show extends Component
{
    public $campaign;

    public function mount($campaign)
    {
        $this->campaign = Campaign::query()
            ->with(['user', 'items', 'promises', 'promiseItems'])
            ->where('user_id', auth()->id())
            ->findOrFail($campaign);
    }

    public function render()
    {
        return view('livewire.campaign.show');
    }
}
