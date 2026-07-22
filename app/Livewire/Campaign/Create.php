<?php

namespace App\Livewire\Campaign;

use App\Livewire\Traits\Alert;
use App\Models\Campaign;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    use Alert;

    public ?string $name = null;

    public ?string $description = null;

    public ?string $confirmation_deadline = null;

    public ?string $delivery_deadline = null;

    public bool $is_active = true;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.campaign.create');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'confirmation_deadline' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'delivery_deadline' => [
                'required',
                'date',
                'after_or_equal:confirmation_deadline',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'confirmation_deadline.after_or_equal' => 'O campo data limite de confirmação deve ser uma data posterior ou igual a hoje.',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'confirmation_deadline' => 'data limite de confirmação',
            'delivery_deadline' => 'data limite de entrega',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        Campaign::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        $this->dispatch('created');

        $this->reset();
        $this->is_active = true;

        $this->success();
    }
}
