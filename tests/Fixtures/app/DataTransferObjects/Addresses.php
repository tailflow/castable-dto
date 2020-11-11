<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests\Fixtures\App\DataTransferObjects;

use Tailflow\DataTransferObjects\CastableDataTransferObjectCollection;

class Addresses extends CastableDataTransferObjectCollection
{
    protected static string $itemClass = Address::class;

    public function current(): Address
    {
        return parent::current();
    }
}