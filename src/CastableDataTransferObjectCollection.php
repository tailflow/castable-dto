<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Spatie\DataTransferObject\DataTransferObjectCollection;
use Tailflow\DataTransferObjects\Casts\DataTransferObject as DataTransferObjectCast;

abstract class CastableDataTransferObjectCollection extends DataTransferObjectCollection implements Castable
{
    protected static string $itemClass;

    public function __construct(array $collection = [])
    {
        $itemClass = static::$itemClass;

        $this->collection = array_map(function($item) use ($itemClass) {
            return is_array($item) ? new $itemClass($item) : $item;
        }, $collection);
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

        return new static($itemClass::arrayOf($collection));
    }
}