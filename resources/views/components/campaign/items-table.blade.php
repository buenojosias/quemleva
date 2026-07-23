<?php

use App\Enums\CategoryEnum;
use App\Models\Campaign;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public int $quantity = 10;
    public string $category = '';

    public Campaign $campaign;

    public function mount($campaign): void
    {
        $this->campaign = $campaign;
    }

    #[Computed]
    public function items()
    {
        $query = $this->campaign->items();

        if ($this->category) {
            $query->where('category', $this->category);
        }

        return $query->paginate($this->quantity);
    }

    #[Computed]
    public function categoryOptions(): array
    {
        return [
            [
                'label' => 'Todas',
                'value' => '',
            ],
            ...array_map(
                fn (CategoryEnum $category): array => [
                    'label' => $category->value,
                    'value' => $category->value,
                ],
                CategoryEnum::cases(),
            ),
        ];
    }

    public function with(): array
    {
        return [
            'headers' => [
                ['index' => 'item', 'label' => 'Item'],
                ['index' => 'category', 'label' => 'Categoria'],
                ['index' => 'quantity', 'label' => 'Quantidade'],
                ['index' => 'date', 'label' => 'Prazo de entrega'],
                ['index' => 'actions'],
            ],
            'rows' => $this->items,
        ];
    }

    #[On('item-created.{campaign.id}')]
    public function refreshItems(): void
    {
        unset($this->items);
    }
};
?>

<div class="space-y-4">

    <div class="flex justify-between items-center gap-4">
        <x-select.native wire:model.live="quantity" label="Itens por página">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </x-select>

        <x-select.native label="Categoria"
            wire:model.live="category"
            :options="$this->categoryOptions()"
            select="label:label|value:value" />
    </div>

    <x-table :$headers :$rows>
        @interact('column_item', $row)
            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ $row->name }}

                @if ($row->note)
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $row->note }}</p>
                @endif
            </div>
        @endinteract
        @interact('column_quantity', $row)
            <div class="space-y-0.5">
            {{ $row->required_quantity }} {{ $row->unit->abbreviation() }}
            <x-progress :percent="rand(0, 100)" color="cyan" sm />
            <div title="Teste">
            <x-progress :percent="rand(0, 100)" color="green" sm />
            </div>
            </div>
        @endinteract
        @interact('column_date', $row)
            {{ $row->delivery_date ? $row->delivery_date->format('d/m/Y') : 'Padrão' }}
        @endinteract
        @interact('column_actions', $row)
            <div class="flex gap-2">
                <x-button icon="pencil-square" title="Editar" sm flat />
                <x-button icon="list-bullet" title="Promessas de doação" sm flat />
            </div>
        @endinteract
    </x-table>

    {{-- {{ $this->items->links() }} --}}
</div>
