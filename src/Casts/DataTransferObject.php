<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class DataTransferObject implements CastsAttributes
{
    /** @var string $class */
    protected $class;

    /**
     * @param string $class The DataTransferObject class to cast to
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * Cast the stored value to a configured DataTransferObject.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return void
     */
    public function get($model, string $key, $value, $attributes)
    {
        if (is_null($value)) {
            return;
        }

        return $this->class::fromJson($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return void
     */
    public function set($model, $key, $value, $attributes)
    {
        if (is_null($value)) {
            return;
        }

        if (is_array($value)) {
            $value = new $this->class($value);
        }

        if ($value instanceof Collection) {
            $value = new $this->class($value->toArray());
        }

        if (!$value instanceof $this->class) {
            throw new InvalidArgumentException("Value must be of type [$this->class], array, or null");
        }

        return $value->toJson();
    }
}
