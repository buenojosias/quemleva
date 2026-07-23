<?php

namespace App\Livewire\Item;

use App\Enums\CategoryEnum;
use App\Enums\UnitEnum;
use App\Models\Campaign;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public Campaign $campaign;

    public bool $modal = false;

    public ?string $category = null;

    public ?string $name = null;

    public ?string $unit = null;

    public ?int $required_quantity = null;

    public ?string $delivery_date = null;

    public ?string $note = null;

    public ?string $successMessage = null;

    public function render(): View
    {
        return view('livewire.item.create');
    }

    public function rules(): array
    {
        return [
            'category' => [
                'required',
                Rule::enum(CategoryEnum::class),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'unit' => [
                'required',
                Rule::enum(UnitEnum::class),
            ],
            'required_quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'delivery_date' => [
                'nullable',
                'date',
            ],
            'note' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'required_quantity' => 'quantidade necessária',
            'delivery_date' => 'data limite de entrega',
        ];
    }

    public function categoryOptions(): array
    {
        return [
            [
                'label' => 'Selecione',
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

    public function unitOptions(): array
    {
        return [
            [
                'label' => 'Selecione',
                'value' => '',
            ],
            ...array_map(
                fn (UnitEnum $unit): array => [
                    'label' => ucfirst($unit->label()),
                    'value' => $unit->value,
                ],
                UnitEnum::cases(),
            ),
        ];
    }

    public function save(): void
    {
        $this->successMessage = null;

        $validated = $this->validate();

        $this->campaign->items()->create($validated);

        $this->reset([
            'name',
            'unit',
            'required_quantity',
            'delivery_date',
            'note',
        ]);

        $this->successMessage = 'Item adicionado com sucesso!';

        $this->dispatch("item-created.{$this->campaign->id}");
    }
}
