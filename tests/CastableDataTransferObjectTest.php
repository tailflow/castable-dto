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
        User::factory()->create([
            'work_address' => [
                'country' => 'jp',
                'city' => 'Tokyo',
                'street' => '4-2-8 Shiba-koen',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'work_address->country' => 'jp',
            'work_address->city' => 'Tokyo',
            'work_address->street' => '4-2-8 Shiba-koen',
        ]);
    }

    /** @test */
    public function it_casts_data_transfer_objects_to_json(): void
    {
        User::factory()->create([
            'work_address' => new Address([
                'country' => 'jp',
                'city' => 'Tokyo',
                'street' => '4-2-8 Shiba-koen',
            ]),
        ]);

        $this->assertDatabaseHas('users', [
            'work_address->country' => 'jp',
            'work_address->city' => 'Tokyo',
            'work_address->street' => '4-2-8 Shiba-koen',
        ]);
    }

    /** @test */
    public function it_casts_json_to_a_data_transfer_object(): void
    {
        $user = User::factory()->create([
            'work_address' => [
                'country' => 'jp',
                'city' => 'Tokyo',
                'street' => '4-2-8 Shiba-koen',
            ],
        ]);

        $user = $user->fresh();

        self::assertInstanceOf(Address::class, $user->work_address);
        self::assertEquals('jp', $user->work_address->country);
        self::assertEquals('Tokyo', $user->work_address->city);
        self::assertEquals('4-2-8 Shiba-koen', $user->work_address->street);
    }

    /** @test */
    public function it_throws_exceptions_for_incorrect_data_structures(): void
    {
        $this->expectException(DataTransferObjectError::class);

        User::factory()->create([
            'work_address' => [
                'bad' => 'thing',
            ],
        ]);
    }

    /** @test */
    public function it_rejects_invalid_types(): void
    {
        $this->expectException(InvalidArgumentException::class);

        User::factory()->create([
            'work_address' => 'string',
        ]);
    }

    /** @test */
    public function it_handles_nullable_columns(): void
    {
        $user = User::factory()->create(['work_address' => null]);

        $this->assertDatabaseHas('users', ['work_address' => null]);

        self::assertNull($user->refresh()->work_address);
    }
}