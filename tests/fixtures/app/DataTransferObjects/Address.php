<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests\Fixtures\App\DataTransferObjects;

use Tailflow\DataTransferObjects\CastableDataTransferObject;

class Address extends CastableDataTransferObject
{
    public string $country;
    public string $city;
    public string $street;
}