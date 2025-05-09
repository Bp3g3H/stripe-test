<?php

namespace App\Services\BillingItems;

use App\Services\BillingItems\Contracts\ItemParser;
use App\Services\BillingItems\Contracts\ItemProvider;

class BillingItemsService
{
    private ItemParser $itemParser;

    private ItemProvider $itemProvider;

    private array $billingItems;

    private string $identifier;

    public function __construct(ItemParser $itemParser, ItemProvider $itemProvider, $user_id)
    {
        $this->itemParser = $itemParser;
        $this->itemProvider = $itemProvider;
        $this->getBillingItems($user_id);
    }

    protected function getBillingItems($user_id): void
    {
        $this->billingItems = $this->itemProvider->get($user_id);
        $this->identifier = $this->itemProvider->getIdentifier();
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getParsedBillingItems(): array
    {
        return $this->itemParser->parse($this->billingItems);
    }
}
