<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests\fixtures\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tailflow\DataTransferObjects\Tests\fixtures\app\DataTransferObjects\Address;
use Tailflow\DataTransferObjects\Tests\fixtures\app\DataTransferObjects\Addresses;
use Tailflow\DataTransferObjects\Tests\Fixtures\Database\Factories\UserFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_address',
        'delivery_addresses'
    ];

    protected $casts = [
        'work_address' => Address::class,
        'delivery_addresses' => Addresses::class
    ];

    protected static function newFactory()
    {
        return new UserFactory();
    }
}