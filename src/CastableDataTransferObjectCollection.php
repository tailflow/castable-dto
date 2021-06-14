<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Tailflow\DataTransferObjects\Casts\DataTransferObject as DataTransferObjectCast;

abstract class CastableDataTransferObjectCollection implements Castable, Arrayable
{
    protected static string $itemClass;
    protected Collection $collection;

    public function __construct(array $collection = [])
    {
        $itemClass = static::$itemClass;

        $this->collection = collect($collection)->map(function($item) use ($itemClass) {
            return is_array($item) ? new $itemClass($item) : $item;
        });
    }

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
        $collection = json_decode($json, true);

        $itemClass = static::$itemClass;

        return collect($itemClass::arrayOf($collection));
    }

    public function toArray(): array
    {
        return $this->collection->toArray();
    }
}
