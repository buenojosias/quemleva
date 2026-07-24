<?php

use App\Enums\CategoryEnum;
use App\Enums\PromiseItemStatusEnum;
use App\Enums\UnitEnum;
use App\Models\Campaign;
use App\Models\Item;
use App\Models\Promise;
use App\Models\PromiseItem;
use App\Models\User;
use Livewire\Livewire;

function campaignForItemPromises(): Campaign
{
    $user = User::factory()->create();

    return Campaign::create([
        'user_id' => $user->id,
        'name' => 'Campanha de Alimentos',
        'description' => 'Arrecadacao de alimentos.',
        'confirmation_deadline' => today()->addDays(10)->toDateString(),
        'delivery_deadline' => today()->addDays(20)->toDateString(),
        'is_active' => true,
    ]);
}

function itemForItemPromises(Campaign $campaign, string $name = 'Arroz'): Item
{
    return $campaign->items()->create([
        'category' => CategoryEnum::FOODS->value,
        'name' => $name,
        'unit' => UnitEnum::KG->value,
        'required_quantity' => 10,
    ]);
}

function promiseItemForItemPromises(Item $item, string $donorName, PromiseItemStatusEnum $status = PromiseItemStatusEnum::PENDING): PromiseItem
{
    $promise = Promise::create([
        'campaign_id' => $item->campaign_id,
        'donor_name' => $donorName,
    ]);

    return $promise->items()->create([
        'item_id' => $item->id,
        'promised_quantity' => 3,
        'status' => $status,
    ]);
}

it('opens the slide and lists promises for the selected item', function () {
    $campaign = campaignForItemPromises();
    $selectedItem = itemForItemPromises($campaign, 'Arroz');
    $otherItem = itemForItemPromises($campaign, 'Feijao');

    promiseItemForItemPromises($selectedItem, 'Maria');
    promiseItemForItemPromises($otherItem, 'Jose');

    Livewire::test('campaign.item-promises', ['campaign' => $campaign])
        ->assertSet('slide', false)
        ->dispatch("open-item-promises.{$campaign->id}", item: $selectedItem->id)
        ->assertSet('slide', true)
        ->assertSee('Arroz')
        ->assertSee('Maria')
        ->assertDontSee('Jose')
        ->assertSee('Pendente')
        ->assertSee('Confirmar');
});

it('confirms a pending promise and updates promised item totals', function () {
    $campaign = campaignForItemPromises();
    $item = itemForItemPromises($campaign);
    $promiseItem = promiseItemForItemPromises($item, 'Maria');

    Livewire::test('campaign.item-promises', ['campaign' => $campaign])
        ->dispatch("open-item-promises.{$campaign->id}", item: $item->id)
        ->call('confirm', $promiseItem->id)
        ->assertDispatched("item-created.{$campaign->id}");

    expect($promiseItem->refresh()->status)->toBe(PromiseItemStatusEnum::PROMISED)
        ->and($promiseItem->promise->refresh()->confirmed_at)->not->toBeNull()
        ->and($item->refresh()->promised_quantity)->toBe(3)
        ->and($item->received_quantity)->toBe(0);
});

it('marks a promise as received and updates received item totals', function () {
    $campaign = campaignForItemPromises();
    $item = itemForItemPromises($campaign);
    $promiseItem = promiseItemForItemPromises($item, 'Maria', PromiseItemStatusEnum::PROMISED);

    Livewire::test('campaign.item-promises', ['campaign' => $campaign])
        ->dispatch("open-item-promises.{$campaign->id}", item: $item->id)
        ->call('receive', $promiseItem->id)
        ->assertDispatched("item-created.{$campaign->id}");

    expect($promiseItem->refresh()->status)->toBe(PromiseItemStatusEnum::RECEIVED)
        ->and($item->refresh()->promised_quantity)->toBe(3)
        ->and($item->received_quantity)->toBe(3);
});

it('deletes a promise item and removes an empty promise', function () {
    $campaign = campaignForItemPromises();
    $item = itemForItemPromises($campaign);
    $promiseItem = promiseItemForItemPromises($item, 'Maria', PromiseItemStatusEnum::PROMISED);
    $promise = $promiseItem->promise;

    Livewire::test('campaign.item-promises', ['campaign' => $campaign])
        ->dispatch("open-item-promises.{$campaign->id}", item: $item->id)
        ->call('delete', $promiseItem->id)
        ->assertDispatched("item-created.{$campaign->id}");

    $this->assertModelMissing($promiseItem);
    $this->assertModelMissing($promise);

    expect($item->refresh()->promised_quantity)->toBe(0)
        ->and($item->received_quantity)->toBe(0);
});
