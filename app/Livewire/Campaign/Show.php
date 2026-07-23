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
            ->with(['user', 'items'])
            ->where('user_id', auth()->id())
            ->findOrFail($campaign);
        $this->campaign->url = route('campaigns.show', $this->campaign);
    }

    public function render()
    {
        return view('livewire.campaign.show');
    }
}
