<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Spatie\DataTransferObject\DataTransferObject;
use Tailflow\DataTransferObjects\Casts\DataTransferObject as DataTransferObjectCast;

abstract class CastableDataTransferObject extends DataTransferObject implements Castable
{
    public static function castUsing(array $arguments): DataTransferObjectCast
    {
        return new DataTransferObjectCast(static::class);
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public static function fromJson($json)
    {
        return new static(json_decode($json, true));
    }
}