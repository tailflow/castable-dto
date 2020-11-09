<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests;

use InvalidArgumentException;
use Spatie\DataTransferObject\DataTransferObjectError;
use Tailflow\DataTransferObjects\Tests\fixtures\app\DataTransferObjects\Address;
use Tailflow\DataTransferObjects\Tests\fixtures\app\Models\User;

class CastableDataTransferObjectTest extends TestCase
{
    /** @test */
    public function it_casts_arrays_to_json(): void
    {
        factory(User::class)->create([
            'address' => [
                'country' => 'jp',
                'city' => 'Tokyo',
                'street' => '4-2-8 Shiba-koen',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'address->country' => 'jp',
            'address->city' => 'Tokyo',
            'address->street' => '4-2-8 Shiba-koen',
        ]);
    }

    /** @test */
    public function it_casts_data_transfer_objects_to_json(): void
    {
        factory(User::class)->create([
            'address' => new Address([
                'country' => 'jp',
                'city' => 'Tokyo',
                'street' => '4-2-8 Shiba-koen',
            ]),
        ]);

        $this->assertDatabaseHas('users', [
            'address->country' => 'jp',
            'address->city' => 'Tokyo',
            'address->street' => '4-2-8 Shiba-koen',
        ]);
    }

    /** @test */
    public function it_casts_json_to_a_data_transfer_object(): void
    {
        $user = factory(User::class)->create([
            'address' => [
                'country' => 'jp',
                'city' => 'Tokyo',
                'street' => '4-2-8 Shiba-koen',
            ],
        ]);

        $user = $user->fresh();

        self::assertInstanceOf(Address::class, $user->address);
        self::assertEquals('jp', $user->address->country);
        self::assertEquals('Tokyo', $user->address->city);
        self::assertEquals('4-2-8 Shiba-koen', $user->address->street);
    }

    /** @test */
    public function it_throws_exceptions_for_incorrect_data_structures(): void
    {
        $this->expectException(DataTransferObjectError::class);

        factory(User::class)->create([
            'address' => [
                'bad' => 'thing',
            ],
        ]);
    }

    /** @test */
    public function it_rejects_invalid_types(): void
    {
        $this->expectException(InvalidArgumentException::class);

        factory(User::class)->create([
            'address' => 'string',
        ]);
    }

    /** @test */
    public function it_handles_nullable_columns(): void
    {
        $user = factory(User::class)->create(['address' => null]);

        $this->assertDatabaseHas('users', ['address' => null]);

        self::assertNull($user->refresh()->address);
    }
}