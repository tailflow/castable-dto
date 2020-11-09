<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests\fixtures\app\DataTransferObjects;

use Tailflow\DataTransferObjects\CastableDataTransferObject;

class Address extends CastableDataTransferObject
{
    /** @var string $country */
    public $country;
    /** @var string $city */
    public $city;
    /** @var string $street */
    public $street;
}