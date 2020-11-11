<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Spatie\DataTransferObject\DataTransferObjectError;
use Tailflow\DataTransferObjects\Tests\fixtures\app\DataTransferObjects\Address;
use Tailflow\DataTransferObjects\Tests\fixtures\app\DataTransferObjects\Addresses;
use Tailflow\DataTransferObjects\Tests\fixtures\app\Models\User;

class CastableDataTransferObjectCollectionTest extends TestCase
{
    /** @test */
    public function it_casts_arrays_to_json(): void
    {
        User::factory()->create([
            'delivery_addresses' => [
                [
                    'country' => 'jp',
                    'city' => 'Tokyo',
                    'street' => '4-2-8 Shiba-koen'
                ],
                [
                    'country' => 'jp',
                    'city' => 'Tokyo',
                    'street' => '5-2-0 Ueno-koen'
                ],
            ],
        ]);

        self::assertTrue(DB::table('users')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[0].country") = "jp"')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[0].city") = "Tokyo"')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[0].street") = "4-2-8 Shiba-koen"')
            ->exists());

        self::assertTrue(DB::table('users')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[1].country") = "jp"')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[1].city") = "Tokyo"')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[1].street") = "5-2-0 Ueno-koen"')
            ->exists());
    }

    /** @test */
    public function it_casts_data_transfer_objects_to_json(): void
    {
        User::factory()->create([
            'delivery_addresses' => new Addresses(
                [
                    new Address([
                        'country' => 'jp',
                        'city' => 'Tokyo',
                        'street' => '4-2-8 Shiba-koen'
                    ]),
                    new Address([
                        'country' => 'jp',
                        'city' => 'Tokyo',
                        'street' => '5-2-0 Ueno-koen'
                    ]),
                ]
            ),
        ]);

        self::assertTrue(DB::table('users')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[0].country") = "jp"')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[0].city") = "Tokyo"')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[0].street") = "4-2-8 Shiba-koen"')
            ->exists());

        self::assertTrue(DB::table('users')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[1].country") = "jp"')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[1].city") = "Tokyo"')
            ->whereRaw('JSON_EXTRACT(delivery_addresses, "$[1].street") = "5-2-0 Ueno-koen"')
            ->exists());
    }

    /** @test */
    public function it_casts_json_to_a_data_transfer_object(): void
    {
        $user = User::factory()->create([
            'delivery_addresses' => [
                [
                    'country' => 'jp',
                    'city' => 'Tokyo',
                    'street' => '4-2-8 Shiba-koen'
                ],
                [
                    'country' => 'jp',
                    'city' => 'Tokyo',
                    'street' => '5-2-0 Ueno-koen'
                ],
            ],
        ]);

        $user = $user->fresh();

        self::assertInstanceOf(Addresses::class, $user->delivery_addresses);

        self::assertEquals('jp', $user->delivery_addresses[0]->country);
        self::assertEquals('Tokyo', $user->delivery_addresses[0]->city);
        self::assertEquals('4-2-8 Shiba-koen', $user->delivery_addresses[0]->street);

        self::assertEquals('jp', $user->delivery_addresses[1]->country);
        self::assertEquals('Tokyo', $user->delivery_addresses[1]->city);
        self::assertEquals('5-2-0 Ueno-koen', $user->delivery_addresses[1]->street);
    }

    /** @test */
    public function it_throws_exceptions_for_incorrect_data_structures(): void
    {
        $this->expectException(DataTransferObjectError::class);

        User::factory()->create([
            'delivery_addresses' => [
                ['bad' => 'thing'],
            ],
        ]);
    }

    /** @test */
    public function it_rejects_invalid_types(): void
    {
        $this->expectException(InvalidArgumentException::class);

        User::factory()->create([
            'delivery_addresses' => 'string',
        ]);
    }

    /** @test */
    public function it_handles_nullable_columns(): void
    {
        $user = User::factory()->create(['delivery_addresses' => null]);

        $this->assertDatabaseHas('users', ['delivery_addresses' => null]);

        self::assertNull($user->refresh()->address);
    }
}