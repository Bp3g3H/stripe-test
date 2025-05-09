<?php

namespace App\Services\BillingItems\Contracts;

interface ItemProvider
{
    public function getIdentifier(): string;

    public function get($user_id): array;
}
