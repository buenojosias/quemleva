<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public $campaign;

    public function mount($campaign)
    {
        $this->campaign = $campaign;
    }

    #[Computed]
    public function items()
    {
        return $this->campaign->items;
    }
};
?>

<div>
    @dump($this->items)
</div>