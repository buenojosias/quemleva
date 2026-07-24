<?php

namespace App\Livewire\Campaign;

use App\Models\Campaign;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public string $campaignId;

    public function mount(Campaign|int|string $campaign): void
    {
        $this->campaignId = $campaign instanceof Campaign
            ? (string) $campaign->getKey()
            : (string) $campaign;
    }

    #[Computed]
    public function campaign(): Campaign
    {
        return Campaign::query()
            ->where('user_id', auth()->id())
            ->findOrFail($this->campaignId);
    }

    #[Computed]
    public function campaignUrl(): string
    {
        return route('campaigns.show', $this->campaign);
    }

    public function render(): View
    {
        return view('livewire.campaign.show');
    }
}
