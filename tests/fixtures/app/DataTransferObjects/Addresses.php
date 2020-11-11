<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests\fixtures\app\DataTransferObjects;

use Tailflow\DataTransferObjects\CastableDataTransferObjectCollection;

class Addresses extends CastableDataTransferObjectCollection
{
    protected static $itemClass = Address::class;

    public function current(): Address
    {
        return parent::current();
    }
}