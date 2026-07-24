<?php

use App\Enums\PromiseItemStatusEnum;
use App\Models\Campaign;
use App\Models\Item;
use App\Models\PromiseItem;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

new class () extends Component {
    use Interactions;

    public bool $slide = false;

    public Campaign $campaign;

    public ?Item $item = null;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    #[Computed]
    public function promiseItems(): Collection
    {
        if (! $this->item) {
            return collect();
        }

        return PromiseItem::query()
            ->with('promise')
            ->where('item_id', $this->item->id)
            ->whereHas('promise', fn ($query) => $query->where('campaign_id', $this->campaign->id))
            ->latest()
            ->get();
    }

    // #[On('open-item-promises.{campaign.id}')]
    #[On('open-item-promises')]
    public function open(int $item): void
    {
        $this->item = $this->campaign->items()->findOrFail($item);
        $this->slide = true;

        unset($this->promiseItems);
    }

    public function confirm(int $promiseItem): void
    {
        $promiseItem = $this->findPromiseItem($promiseItem);

        $promiseItem->update([
            'status' => PromiseItemStatusEnum::PROMISED,
        ]);

        $promiseItem->promise->update([
            'confirmed_at' => $promiseItem->promise->confirmed_at ?? now(),
        ]);

        $this->refreshItemQuantities();
        $this->toast()->success('Promessa confirmada com sucesso.')->send();
    }

    public function receive(int $promiseItem): void
    {
        $promiseItem = $this->findPromiseItem($promiseItem);

        $promiseItem->update([
            'status' => PromiseItemStatusEnum::RECEIVED,
        ]);

        $promiseItem->promise->update([
            'confirmed_at' => $promiseItem->promise->confirmed_at ?? now(),
        ]);

        $this->refreshItemQuantities();
        $this->toast()->success('Promessa marcada como recebida.')->send();
    }

    public function askToDelete(int $promiseItem): void
    {
        $this->findPromiseItem($promiseItem);

        $this->dialog()
            ->question('Excluir promessa?', 'Esta ação não poderá ser desfeita.')
            ->confirm('Excluir', 'delete', $promiseItem)
            ->cancel('Cancelar')
            ->send();
    }

    public function delete(int $promiseItem): void
    {
        $promiseItem = $this->findPromiseItem($promiseItem);
        $promise = $promiseItem->promise;

        $promiseItem->delete();

        if (! $promise->items()->exists()) {
            $promise->delete();
        }

        $this->refreshItemQuantities();
        $this->toast()->success('Promessa excluída com sucesso.')->send();
    }

    public function statusLabel(PromiseItem $promiseItem): string
    {
        return match ($promiseItem->status) {
            PromiseItemStatusEnum::PENDING => 'Pendente',
            PromiseItemStatusEnum::PROMISED => 'Prometida',
            PromiseItemStatusEnum::RECEIVED, PromiseItemStatusEnum::DELIVERED => 'Recebida',
            PromiseItemStatusEnum::CANCELED => 'Cancelada',
        };
    }

    public function statusColor(PromiseItem $promiseItem): string
    {
        return match ($promiseItem->status) {
            PromiseItemStatusEnum::PENDING => 'yellow',
            PromiseItemStatusEnum::PROMISED => 'cyan',
            PromiseItemStatusEnum::RECEIVED, PromiseItemStatusEnum::DELIVERED => 'green',
            PromiseItemStatusEnum::CANCELED => 'red',
        };
    }

    private function findPromiseItem(int $promiseItem): PromiseItem
    {
        return PromiseItem::query()
            ->with('promise')
            ->whereKey($promiseItem)
            ->where('item_id', $this->item?->id)
            ->whereHas('promise', fn ($query) => $query->where('campaign_id', $this->campaign->id))
            ->firstOrFail();
    }

    private function refreshItemQuantities(): void
    {
        if (! $this->item) {
            return;
        }

        $promisedQuantity = PromiseItem::query()
            ->where('item_id', $this->item->id)
            ->whereIn('status', [
                PromiseItemStatusEnum::PROMISED->value,
                PromiseItemStatusEnum::RECEIVED->value,
                PromiseItemStatusEnum::DELIVERED->value,
            ])
            ->sum('promised_quantity');

        $receivedQuantity = PromiseItem::query()
            ->where('item_id', $this->item->id)
            ->whereIn('status', [
                PromiseItemStatusEnum::RECEIVED->value,
                PromiseItemStatusEnum::DELIVERED->value,
            ])
            ->sum('promised_quantity');

        $this->item->update([
            'promised_quantity' => $promisedQuantity,
            'received_quantity' => $receivedQuantity,
        ]);

        $this->item->refresh();

        unset($this->promiseItems);

        $this->dispatch("item-created.{$this->campaign->id}");
    }
};
