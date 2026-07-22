<div>
    <div class="header">
        <h1>Minhas campanhas</h1>
        <div>
            <livewire:campaign.create />
        </div>
    </div>
    
    <div class="space-y-4">
        @forelse ($this->campaigns as $campaign)
            {{-- <livewire:campaign.card :campaign="$campaign" :wire:key="$campaign->id" /> --}}
            <x-card>
                <small class="text-gray-500">{{ $campaign->created_at->format('d/m/Y') }}</small>
                <p>
                    <a href="{{ route('campaigns.show', $campaign) }}">{{ $campaign->name }}</a>
                </p>
            </x-card>
        @empty
            <div class="col-span-full">
                <x-alert color="secondary" light icon="heart" title="Você ainda não tem campanhas">
                    Que tal criar sua primeira campanha? Clique no botão acima para começar!
                </x-alert>
            </div>
        @endforelse
        <div class="mt-4">
            {{ $this->campaigns->links() }}
        </div>
    </div>

</div>
