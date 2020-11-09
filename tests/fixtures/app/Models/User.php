<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests\fixtures\app\Models;

use Illuminate\Database\Eloquent\Model;
use Tailflow\DataTransferObjects\Tests\fixtures\app\DataTransferObjects\Address;

class User extends Model
{
    protected $fillable = ['name', 'address'];

    protected $casts = ['address' => Address::class];
}