<?php

namespace Tests\Feature;

use App\Casts\DateTimeCast;
use Database\Factories\CdrFactory;
use Database\Factories\EvseFactory;
use Database\Factories\OperatorFactory;
use Illuminate\Support\Str;
use Tests\TestCase;

class GlobalTest extends TestCase
{
    /**
     * Route : GET /ocpi/xxxxxxxxxx/yyyyyyyyyy.
     */
    public function testOcpiRandomRoute(): void
    {
        $response = self::get('/ocpi/'.Str::random(10).'/'.Str::random(10));
        $response->assertNoContent(401);
    }

    /**
     * Route : GET /ocpi.
     */
    public function testGetOcpiRouteNoBearer(): void
    {
        $response = self::get('/ocpi');
        $response->assertNoContent(401);
    }

    /**
     * Route : PUT /ocpi.
     */
    public function testPutOcpiRouteNoBearer(): void
    {
        $response = self::put('/ocpi');
        $response->assertNoContent(401);
    }

    /**
     * Route : PUT /ocpi.
     */
    public function testPutOcpiRouteBadBearer(): void
    {
        $response = self::withHeader('Authorization', 'Bearer '.Str::random(64))
            ->get('/ocpi');
        $response->assertNoContent(401);
    }

    /**
     * Route : PUT /ocpi.
     */
    public function testPutOcpiRouteGoodBearer(): void
    {
        $operator = OperatorFactory::new()->create();
        $response = self::withHeader('Authorization', 'Bearer '.$operator->access_token)
            ->put('/ocpi');
        $operator->delete();
        $response->assertNoContent(404);
    }

    /**
     * Route : GET /ocpi.
     */
    public function testGetOcpiRouteGoodBearer(): void
    {
        $operator = OperatorFactory::new()->create();
        $response = self::withHeader('Authorization', 'Bearer '.$operator->access_token)
            ->get('/ocpi');
        $operator->delete();
        $response->assertNoContent(404);
    }

    /**
     * Route : GET /ocpi/cdrs.
     */
    public function testGetCdrsRouteBadBearerNoRef(): void
    {
        $response = self::withHeader('Authorization', 'Bearer '.Str::random(64))
            ->get('/ocpi/cdrs');
        $response->assertNoContent(401);
    }

    /**
     * Route : GET /ocpi/cdrs.
     */
    public function testGetCdrsRouteGoodBearerNoRef(): void
    {
        $operator = OperatorFactory::new()->create();
        $response = self::withHeader('Authorization', 'Bearer '.$operator->access_token)
            ->get('/ocpi/cdrs');
        $operator->delete();
        $response->assertNoContent(404);
    }

    /**
     * Route : GET /ocpi/cdrs/{ref}.
     */
    public function testGetCdrsRouteGoodBearerBadRef(): void
    {
        $operator = OperatorFactory::new()->create();
        $response = self::withHeader('Authorization', 'Bearer '.$operator->access_token)
            ->get('/ocpi/cdrs/'.Str::random(36));
        $operator->delete();
        $response->assertNoContent(401);
    }

    /**
     * Route : GET /ocpi/cdrs/{ref}.
     */
    public function testGetCdrsRouteBadBearerAndGoodRef(): void
    {
        $operator = OperatorFactory::new()->create();
        $response = self::withHeader('Authorization', 'Bearer '.$operator->access_token)
            ->get('/ocpi/cdrs/'.Str::random(36));
        $operator->delete();
        $response->assertNoContent(401);
    }

    /**
     * Route : GET /ocpi/cdrs/{ref}.
     */
    public function testGetCdrsRouteGoodBearerGoodRefNotEvseOwner(): void
    {
        // arrange
        $operator = OperatorFactory::new()->create();
        $badCdr = CdrFactory::new()->makeOne();

        // act
        $response = self::withHeader('Authorization', 'Bearer '.$operator->access_token)
            ->get('/ocpi/cdrs/'.$badCdr->ref);
        $operator->delete();
        $badCdr->evse->operator->delete();

        // assert
        $response->assertNoContent(401);
    }

    /**
     * Route : GET /ocpi/cdrs/{ref}.
     */
    public function testGetCdrsRouteGoodBearerGoodRefEvseOwner(): void
    {
        // arrange
        $goodCdr = CdrFactory::new()->create();

        // act
        $response = self::withHeader('Authorization', 'Bearer '.$goodCdr->evse->operator->access_token)
            ->getJson('/ocpi/cdrs/'.$goodCdr->ref);
        $goodCdr->evse->operator->delete();

        // assert
        $response->assertExactJson([
            'id' => $goodCdr->ref,
            'evse_uid' => $goodCdr->evse->ref,
            'start_datetime' => $goodCdr->start_datetime->format(DateTimeCast::DATE_TIME_FORMAT),
            'end_datetime' => $goodCdr->end_datetime->format(DateTimeCast::DATE_TIME_FORMAT),
            'total_energy' => $goodCdr->total_energy,
            'total_cost' => $goodCdr->total_cost,
        ]);
    }

    /**
     * Route : PUT /ocpi/cdrs
     */
    public function testPutCdrsRouteGoodBearerGoodRefNotEvseOwner(): void
    {
        // arrange
        $badEvse = EvseFactory::new()->create();
        $goodCdr = CdrFactory::new()->create();
        $data = [
            'id' => $goodCdr->ref,
            'evse_uid' => $badEvse->ref,
            'start_datetime' => now()->subHour()->toIso8601ZuluString(),
            'end_datetime' => now()->toIso8601ZuluString(),
            'total_energy' => rand(1, 1000),
            'total_cost' => rand(1, 10000),
        ];

        // act
        $response = self::withHeader('Authorization', 'Bearer '.$goodCdr->evse->operator->access_token)
            ->putJson('/ocpi/cdrs', $data);
        $goodCdr->evse->operator->delete();
        $badEvse->operator->delete();

        //assert
        $response->assertNoContent(404);
    }

    /**
     * Route : PUT /ocpi/cdrs
     */
    public function testPutCdrsRouteGoodBearerNewRefEvseOwner(): void
    {
        // arrange
        $evse = EvseFactory::new()->create();
        $data = [
            'id' => Str::random(36),
            'evse_uid' => $evse->ref,
            'start_datetime' => now()->subHour()->toIso8601ZuluString(),
            'end_datetime' => now()->toIso8601ZuluString(),
            'total_energy' => rand(1, 1000),
            'total_cost' => rand(1, 10000),
        ];

        // act
        $response = self::withHeader('Authorization', 'Bearer '.$evse->operator->access_token)
            ->putJson('/ocpi/cdrs', $data);
        $evse->operator->delete();

        // assert
        $response->assertExactJson($data);
    }

    /**
     * Route : PUT /ocpi/cdrs
     */
    public function testPutCdrsRouteGoodBearerGoodRefEvseOwnerGoodJsonStructure(): void
    {
        // arrange
        $goodCdr = CdrFactory::new()->create();
        $data = [
            'id' => $goodCdr->ref,
            'evse_uid' => $goodCdr->evse->ref,
            'start_datetime' => now()->subHour()->toIso8601ZuluString(),
            'end_datetime' => now()->toIso8601ZuluString(),
            'total_energy' => rand(1, 1000),
            'total_cost' => rand(1, 10000),
        ];

        // act
        $response = self::withHeader('Authorization', 'Bearer '.$goodCdr->evse->operator->access_token)
            ->putJson('/ocpi/cdrs', $data);
        $goodCdr->evse->operator->delete();

        // assert
        $response->assertExactJson($data);
    }

    /**
     * Route : PUT /ocpi/cdrs
     */
    public function testPutCdrsRouteGoodBearerGoodRefEvseOwnerBadJsonStructure(): void
    {
        // arrange
        $goodCdr = CdrFactory::new()->create();
        $data = [
            'id' => $goodCdr->ref,
            'evse_uid' => $goodCdr->evse->ref,
            'start_datetime' => now()->subHour()->toIso8601ZuluString(),
            // 'end_datetime' => now()->toIso8601ZuluString(),
            'total_energy' => rand(1, 1000),
            'total_cost' => rand(1, 10000),
        ];

        // act
        $response = self::withHeader('Authorization', 'Bearer '.$goodCdr->evse->operator->access_token)
            ->putJson('/ocpi/cdrs', $data);
        $goodCdr->evse->operator->delete();

        // assert
        $response->assertNoContent(404);
    }
}
