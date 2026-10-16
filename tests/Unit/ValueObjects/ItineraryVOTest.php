<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\DomainStructures\Utils\Associative as StrictAssociative;
use AndyDefer\PhpVo\Collections\CoordinatesVOCollection;
use AndyDefer\PhpVo\Collections\ItineraryLegVOCollection;
use AndyDefer\PhpVo\Enums\DistanceUnit;
use AndyDefer\PhpVo\Enums\ItineraryAlgorithm;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\ItineraryLegVO;
use AndyDefer\PhpVo\ValueObjects\ItinerarySummaryVO;
use AndyDefer\PhpVo\ValueObjects\ItineraryVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ItineraryVOTest extends TestCase
{
    private const PARIS = ['latitude' => 48.8566, 'longitude' => 2.3522];

    private const LONDON = ['latitude' => 51.5074, 'longitude' => -0.1278];

    private const LILLE = ['latitude' => 50.6292, 'longitude' => 3.0573];

    private const REIMS = ['latitude' => 49.2583, 'longitude' => 4.0317];

    private const LE_HAVRE = ['latitude' => 49.4944, 'longitude' => 0.1079];

    private function paris(): CoordinatesVO
    {
        return CoordinatesVO::from(self::PARIS);
    }

    private function london(): CoordinatesVO
    {
        return CoordinatesVO::from(self::LONDON);
    }

    private function lille(): CoordinatesVO
    {
        return CoordinatesVO::from(self::LILLE);
    }

    private function reims(): CoordinatesVO
    {
        return CoordinatesVO::from(self::REIMS);
    }

    private function leHavre(): CoordinatesVO
    {
        return CoordinatesVO::from(self::LE_HAVRE);
    }

    private function emptyIntermediates(): CoordinatesVOCollection
    {
        return new CoordinatesVOCollection;
    }

    private function intermediates(CoordinatesVO ...$points): CoordinatesVOCollection
    {
        $collection = new CoordinatesVOCollection;

        foreach ($points as $point) {
            $collection->add($point);
        }

        return $collection;
    }

    private function syntheticIntermediates(int $count): CoordinatesVOCollection
    {
        $collection = new CoordinatesVOCollection;

        for ($i = 0; $i < $count; $i++) {
            $collection->add(CoordinatesVO::from([
                'latitude' => 41.0 + ($i * 0.5),
                'longitude' => -9.0 + ($i * 0.7),
            ]));
        }

        return $collection;
    }

    // ========== CONSTRUCTION ==========

    public function test_create_itinerary_without_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $this->assertSame(DistanceUnit::KILOMETRE, $itinerary->getUnit());
        $this->assertSame(ItineraryAlgorithm::GREEDY, $itinerary->getAlgorithm());
        $this->assertSame(0, $itinerary->getIntermediates()->count());
        $this->assertTrue($itinerary->getStart()->equals($this->paris()));
        $this->assertTrue($itinerary->getEnd()->equals($this->london()));
    }

    public function test_create_itinerary_with_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims()),
            $this->london(),
        );

        $this->assertSame(2, $itinerary->getIntermediates()->count());
    }

    public function test_create_itinerary_with_custom_unit(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::METRE,
        );

        $this->assertSame(DistanceUnit::METRE, $itinerary->getUnit());
    }

    public function test_create_itinerary_with_custom_algorithm(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille()),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $this->assertSame(ItineraryAlgorithm::HELD_KARP, $itinerary->getAlgorithm());
    }

    // ========== INTERMEDIATE LIMIT ==========

    public function test_constructor_rejects_more_than_max_intermediates(): void
    {
        $intermediates = $this->syntheticIntermediates(ItineraryVO::MAX_INTERMEDIATES + 1);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Itinerary cannot contain more than %d intermediate points, %d given.',
            ItineraryVO::MAX_INTERMEDIATES,
            ItineraryVO::MAX_INTERMEDIATES + 1,
        ));

        new ItineraryVO($this->paris(), $intermediates, $this->london());
    }

    public function test_constructor_accepts_exactly_max_intermediates_with_two_opt(): void
    {
        $intermediates = $this->syntheticIntermediates(ItineraryVO::MAX_INTERMEDIATES);

        $itinerary = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::TWO_OPT,
        );

        $this->assertSame(ItineraryVO::MAX_INTERMEDIATES, $itinerary->getIntermediates()->count());
    }

    // ========== ALGORITHM LIMITS ==========

    public function test_brute_force_rejects_more_than_eight_intermediates(): void
    {
        $intermediates = $this->syntheticIntermediates(9);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Algorithm "%s" supports at most %d intermediate points, %d given.',
            ItineraryAlgorithm::BRUTE_FORCE->value,
            ItineraryAlgorithm::BRUTE_FORCE->maxIntermediates(),
            9,
        ));

        new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::BRUTE_FORCE,
        );
    }

    public function test_brute_force_accepts_exactly_eight_intermediates(): void
    {
        $intermediates = $this->syntheticIntermediates(8);

        $itinerary = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::BRUTE_FORCE,
        );

        $this->assertSame(8, $itinerary->getIntermediates()->count());
    }

    public function test_held_karp_rejects_more_than_fifteen_intermediates(): void
    {
        $intermediates = $this->syntheticIntermediates(16);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Algorithm "%s" supports at most %d intermediate points, %d given.',
            ItineraryAlgorithm::HELD_KARP->value,
            ItineraryAlgorithm::HELD_KARP->maxIntermediates(),
            16,
        ));

        new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );
    }

    public function test_held_karp_accepts_exactly_fifteen_intermediates(): void
    {
        $intermediates = $this->syntheticIntermediates(15);

        $itinerary = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $this->assertSame(15, $itinerary->getIntermediates()->count());
    }

    public function test_greedy_accepts_up_to_max_intermediates(): void
    {
        $intermediates = $this->syntheticIntermediates(ItineraryVO::MAX_INTERMEDIATES);

        $itinerary = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::GREEDY,
        );

        $this->assertSame(ItineraryVO::MAX_INTERMEDIATES, $itinerary->getIntermediates()->count());
    }

    public function test_two_opt_accepts_up_to_max_intermediates(): void
    {
        $intermediates = $this->syntheticIntermediates(ItineraryVO::MAX_INTERMEDIATES);

        $itinerary = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::TWO_OPT,
        );

        $this->assertSame(ItineraryVO::MAX_INTERMEDIATES, $itinerary->getIntermediates()->count());
    }

    // ========== WITH ALGORITHM — LIMITS ==========

    public function test_with_algorithm_throws_when_count_exceeds_target_limit(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->syntheticIntermediates(10),
            $this->london(),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Algorithm "%s" supports at most %d intermediate points, %d given.',
            ItineraryAlgorithm::BRUTE_FORCE->value,
            ItineraryAlgorithm::BRUTE_FORCE->maxIntermediates(),
            10,
        ));

        $itinerary->withAlgorithm(ItineraryAlgorithm::BRUTE_FORCE);
    }

    public function test_with_algorithm_succeeds_when_count_fits_target_limit(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->syntheticIntermediates(10),
            $this->london(),
        );

        $updated = $itinerary->withAlgorithm(ItineraryAlgorithm::TWO_OPT);

        $this->assertSame(ItineraryAlgorithm::TWO_OPT, $updated->getAlgorithm());
    }

    // ========== WITH UNIT ==========

    public function test_with_unit_returns_new_instance(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $updated = $itinerary->withUnit(DistanceUnit::METRE);

        $this->assertNotSame($itinerary, $updated);
        $this->assertSame(DistanceUnit::KILOMETRE, $itinerary->getUnit());
        $this->assertSame(DistanceUnit::METRE, $updated->getUnit());
    }

    public function test_with_unit_preserves_algorithm(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $updated = $itinerary->withUnit(DistanceUnit::METRE);

        $this->assertSame(ItineraryAlgorithm::HELD_KARP, $updated->getAlgorithm());
    }

    // ========== WITH ALGORITHM ==========

    public function test_with_algorithm_returns_new_instance(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $updated = $itinerary->withAlgorithm(ItineraryAlgorithm::BRUTE_FORCE);

        $this->assertNotSame($itinerary, $updated);
        $this->assertSame(ItineraryAlgorithm::GREEDY, $itinerary->getAlgorithm());
        $this->assertSame(ItineraryAlgorithm::BRUTE_FORCE, $updated->getAlgorithm());
    }

    public function test_with_algorithm_preserves_unit_and_points(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille()),
            $this->london(),
            DistanceUnit::METRE,
        );

        $updated = $itinerary->withAlgorithm(ItineraryAlgorithm::HELD_KARP);

        $this->assertSame(DistanceUnit::METRE, $updated->getUnit());
        $this->assertSame(1, $updated->getIntermediates()->count());
    }

    // ========== WITH BEST ALGORITHM ==========

    public function test_with_best_algorithm_selects_brute_force_under_eight(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->syntheticIntermediates(5),
            $this->london(),
        );

        $best = $itinerary->withBestAlgorithm();

        $this->assertSame(ItineraryAlgorithm::BRUTE_FORCE, $best->getAlgorithm());
    }

    public function test_with_best_algorithm_selects_held_karp_between_nine_and_fifteen(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->syntheticIntermediates(12),
            $this->london(),
        );

        $best = $itinerary->withBestAlgorithm();

        $this->assertSame(ItineraryAlgorithm::HELD_KARP, $best->getAlgorithm());
    }

    public function test_with_best_algorithm_selects_two_opt_above_fifteen(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->syntheticIntermediates(20),
            $this->london(),
        );

        $best = $itinerary->withBestAlgorithm();

        $this->assertSame(ItineraryAlgorithm::TWO_OPT, $best->getAlgorithm());
    }

    public function test_with_best_algorithm_with_empty_intermediates_returns_brute_force(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $best = $itinerary->withBestAlgorithm();

        $this->assertSame(ItineraryAlgorithm::BRUTE_FORCE, $best->getAlgorithm());
    }

    // ========== ORDERED PATH — GREEDY ==========

    public function test_greedy_ordered_path_without_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $path = $itinerary->getOrderedPath()->toArray();

        $this->assertCount(2, $path);
        $this->assertTrue($path[0]->equals($this->paris()));
        $this->assertTrue($path[1]->equals($this->london()));
    }

    public function test_greedy_ordered_path_reorders_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->reims(), $this->leHavre(), $this->lille()),
            $this->london(),
        );

        $path = $itinerary->getOrderedPath()->toArray();

        $this->assertCount(5, $path);
        $this->assertTrue($path[0]->equals($this->paris()));
        $this->assertTrue($path[1]->equals($this->reims()));
        $this->assertTrue($path[2]->equals($this->lille()));
        $this->assertTrue($path[3]->equals($this->leHavre()));
        $this->assertTrue($path[4]->equals($this->london()));
    }

    // ========== ORDERED PATH — BRUTE FORCE ==========

    public function test_brute_force_returns_path_with_same_start_and_end(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims(), $this->leHavre()),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::BRUTE_FORCE,
        );

        $path = $itinerary->getOrderedPath()->toArray();

        $this->assertCount(5, $path);
        $this->assertTrue($path[0]->equals($this->paris()));
        $this->assertTrue($path[4]->equals($this->london()));
    }

    public function test_brute_force_keeps_all_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims(), $this->leHavre()),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::BRUTE_FORCE,
        );

        $path = $itinerary->getOrderedPath()->toArray();
        $expected = [$this->lille(), $this->reims(), $this->leHavre()];

        for ($i = 1; $i <= 3; $i++) {
            $matched = false;

            foreach ($expected as $point) {
                if ($path[$i]->equals($point)) {
                    $matched = true;
                    break;
                }
            }

            $this->assertTrue($matched, "Intermediate at position {$i} does not match any input point.");
        }
    }

    public function test_brute_force_does_not_exceed_greedy_distance(): void
    {
        $intermediates = $this->intermediates(
            $this->lille(),
            $this->reims(),
            $this->leHavre(),
        );

        $greedy = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
        );

        $brute = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::BRUTE_FORCE,
        );

        $this->assertLessThanOrEqual(
            $greedy->totalDistance()->getValue(),
            $brute->totalDistance()->getValue(),
        );
    }

    public function test_brute_force_with_empty_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::BRUTE_FORCE,
        );

        $path = $itinerary->getOrderedPath()->toArray();

        $this->assertCount(2, $path);
        $this->assertTrue($path[0]->equals($this->paris()));
        $this->assertTrue($path[1]->equals($this->london()));
    }

    // ========== ORDERED PATH — HELD KARP ==========

    public function test_held_karp_returns_path_with_same_start_and_end(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims(), $this->leHavre()),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $path = $itinerary->getOrderedPath()->toArray();

        $this->assertCount(5, $path);
        $this->assertTrue($path[0]->equals($this->paris()));
        $this->assertTrue($path[4]->equals($this->london()));
    }

    public function test_held_karp_matches_brute_force_distance_for_small_input(): void
    {
        $intermediates = $this->intermediates(
            $this->lille(),
            $this->reims(),
            $this->leHavre(),
        );

        $brute = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::BRUTE_FORCE,
        );

        $karp = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $this->assertSame(
            $brute->totalDistance()->getValue(),
            $karp->totalDistance()->getValue(),
        );
    }

    public function test_held_karp_does_not_exceed_greedy_distance(): void
    {
        $intermediates = $this->intermediates(
            $this->lille(),
            $this->reims(),
            $this->leHavre(),
        );

        $greedy = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
        );

        $karp = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $this->assertLessThanOrEqual(
            $greedy->totalDistance()->getValue(),
            $karp->totalDistance()->getValue(),
        );
    }

    public function test_held_karp_with_empty_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $path = $itinerary->getOrderedPath()->toArray();

        $this->assertCount(2, $path);
        $this->assertTrue($path[0]->equals($this->paris()));
        $this->assertTrue($path[1]->equals($this->london()));
    }

    // ========== ORDERED PATH — TWO OPT ==========

    public function test_two_opt_returns_path_with_same_start_and_end(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims(), $this->leHavre()),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::TWO_OPT,
        );

        $path = $itinerary->getOrderedPath()->toArray();

        $this->assertCount(5, $path);
        $this->assertTrue($path[0]->equals($this->paris()));
        $this->assertTrue($path[4]->equals($this->london()));
    }

    public function test_two_opt_does_not_exceed_greedy_distance(): void
    {
        $intermediates = $this->intermediates(
            $this->lille(),
            $this->reims(),
            $this->leHavre(),
        );

        $greedy = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
        );

        $twoOpt = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::TWO_OPT,
        );

        $this->assertLessThanOrEqual(
            $greedy->totalDistance()->getValue(),
            $twoOpt->totalDistance()->getValue(),
        );
    }

    public function test_two_opt_keeps_all_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims(), $this->leHavre()),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::TWO_OPT,
        );

        $path = $itinerary->getOrderedPath()->toArray();
        $expected = [$this->lille(), $this->reims(), $this->leHavre()];

        for ($i = 1; $i <= 3; $i++) {
            $matched = false;

            foreach ($expected as $point) {
                if ($path[$i]->equals($point)) {
                    $matched = true;
                    break;
                }
            }

            $this->assertTrue($matched, "Intermediate at position {$i} does not match any input point.");
        }
    }

    public function test_two_opt_with_empty_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::TWO_OPT,
        );

        $path = $itinerary->getOrderedPath()->toArray();

        $this->assertCount(2, $path);
        $this->assertTrue($path[0]->equals($this->paris()));
        $this->assertTrue($path[1]->equals($this->london()));
    }

    // ========== ORDERED PATH — MUTATION ==========

    public function test_ordered_path_does_not_mutate_original_intermediates(): void
    {
        $intermediates = $this->intermediates($this->reims(), $this->lille());

        $itinerary = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
        );

        $itinerary->getOrderedPath();

        $this->assertSame(2, $intermediates->count());
        $this->assertTrue($intermediates->first()->equals($this->reims()));
        $this->assertTrue($intermediates->last()->equals($this->lille()));
    }

    // ========== LEGS ==========

    public function test_legs_returns_itinerary_leg_vo_collection(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $legs = $itinerary->legs();

        $this->assertInstanceOf(ItineraryLegVOCollection::class, $legs);
        $this->assertSame(1, $legs->count());
        $this->assertInstanceOf(ItineraryLegVO::class, $legs->first());
    }

    public function test_legs_count_without_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $legs = $itinerary->legs();

        $this->assertSame(1, $legs->count());
        $this->assertSame(0, $legs->first()->getIndex());
        $this->assertTrue($legs->first()->getFrom()->equals($this->paris()));
        $this->assertTrue($legs->first()->getTo()->equals($this->london()));
    }

    public function test_legs_count_with_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims()),
            $this->london(),
        );

        $legs = $itinerary->legs();

        $this->assertSame(3, $legs->count());
        $this->assertSame(
            [0, 1, 2],
            $legs->map(fn (ItineraryLegVO $leg): int => $leg->getIndex())->toArray(),
        );
    }

    public function test_legs_use_itinerary_unit(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::METRE,
        );

        $this->assertSame(DistanceUnit::METRE, $itinerary->legs()->first()->getUnit());
    }

    public function test_leg_distance_between_paris_and_london_is_reasonable(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $leg = $itinerary->legs()->first();

        $this->assertGreaterThan(340, $leg->getDistance()->getValue());
        $this->assertLessThan(350, $leg->getDistance()->getValue());
    }

    // ========== TOTAL DISTANCE ==========

    public function test_total_distance_without_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $distance = $itinerary->totalDistance();

        $this->assertInstanceOf(FloatVO::class, $distance);
        $this->assertGreaterThan(340, $distance->getValue());
        $this->assertLessThan(350, $distance->getValue());
    }

    public function test_total_distance_is_greater_than_direct_distance_with_intermediates(): void
    {
        $direct = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $withIntermediates = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims()),
            $this->london(),
        );

        $this->assertGreaterThan(
            $direct->totalDistance()->getValue(),
            $withIntermediates->totalDistance()->getValue(),
        );
    }

    public function test_total_distance_is_rounded_to_two_decimals(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille()),
            $this->london(),
        );

        $value = $itinerary->totalDistance()->getValue();

        $this->assertSame(round($value, 2), $value);
    }

    // ========== DISTANCE BETWEEN ==========

    public function test_distance_between_uses_itinerary_unit(): void
    {
        $km = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::KILOMETRE,
        );

        $m = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::METRE,
        );

        $kmValue = $km->distanceBetween($this->paris(), $this->london());
        $mValue = $m->distanceBetween($this->paris(), $this->london());

        $this->assertGreaterThan(340, $kmValue);
        $this->assertLessThan(350, $kmValue);
        $this->assertGreaterThan(340000, $mValue);
        $this->assertLessThan(350000, $mValue);
    }

    // ========== SUMMARY ==========

    public function test_summary_returns_itinerary_summary_vo(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $this->assertInstanceOf(ItinerarySummaryVO::class, $itinerary->summary());
    }

    public function test_summary_without_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
        );

        $summary = $itinerary->summary();

        $this->assertSame(1, $summary->getLegCount());
        $this->assertSame(2, $summary->getPointCount());
        $this->assertSame(0, $summary->getIntermediateCount());
        $this->assertSame(DistanceUnit::KILOMETRE, $summary->getUnit());
        $this->assertSame(0, $summary->getLongestLegIndex());
        $this->assertSame(0, $summary->getShortestLegIndex());
    }

    public function test_summary_with_intermediates(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille(), $this->reims()),
            $this->london(),
        );

        $summary = $itinerary->summary();

        $this->assertSame(3, $summary->getLegCount());
        $this->assertSame(4, $summary->getPointCount());
        $this->assertSame(2, $summary->getIntermediateCount());
        $this->assertGreaterThan(0.0, $summary->getAverageLegDistance()->getValue());
        $this->assertGreaterThanOrEqual(
            $summary->getShortestLegDistance()->getValue(),
            $summary->getLongestLegDistance()->getValue(),
        );
    }

    // ========== GET VALUE ==========

    public function test_get_value_returns_strict_associative(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille()),
            $this->london(),
        );

        $this->assertInstanceOf(StrictAssociative::class, $itinerary->getValue());
    }

    public function test_get_value_exposes_input_structure(): void
    {
        $intermediates = $this->intermediates($this->lille());

        $itinerary = new ItineraryVO(
            $this->paris(),
            $intermediates,
            $this->london(),
        );

        $value = $itinerary->getValue();

        $this->assertTrue($value->start->equals($this->paris()));
        $this->assertTrue($value->end->equals($this->london()));
        $this->assertSame($intermediates, $value->intermediates);
        $this->assertSame(DistanceUnit::KILOMETRE, $value->unit);
        $this->assertSame(ItineraryAlgorithm::GREEDY, $value->algorithm);
    }

    public function test_get_value_reflects_custom_algorithm(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::METRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $value = $itinerary->getValue();

        $this->assertSame(DistanceUnit::METRE, $value->unit);
        $this->assertSame(ItineraryAlgorithm::HELD_KARP, $value->algorithm);
    }

    // ========== EQUALS ==========

    public function test_equals_returns_true_for_same_itinerary(): void
    {
        $a = new ItineraryVO($this->paris(), $this->intermediates($this->lille()), $this->london());
        $b = new ItineraryVO($this->paris(), $this->intermediates($this->lille()), $this->london());

        $this->assertTrue($a->equals($b));
    }

    public function test_equals_returns_false_for_different_unit(): void
    {
        $a = new ItineraryVO($this->paris(), $this->emptyIntermediates(), $this->london(), DistanceUnit::KILOMETRE);
        $b = new ItineraryVO($this->paris(), $this->emptyIntermediates(), $this->london(), DistanceUnit::METRE);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_algorithm(): void
    {
        $a = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::GREEDY,
        );

        $b = new ItineraryVO(
            $this->paris(),
            $this->emptyIntermediates(),
            $this->london(),
            DistanceUnit::KILOMETRE,
            ItineraryAlgorithm::HELD_KARP,
        );

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_intermediates(): void
    {
        $a = new ItineraryVO($this->paris(), $this->intermediates($this->lille()), $this->london());
        $b = new ItineraryVO($this->paris(), $this->intermediates($this->reims()), $this->london());

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_start(): void
    {
        $a = new ItineraryVO($this->paris(), $this->emptyIntermediates(), $this->london());
        $b = new ItineraryVO($this->lille(), $this->emptyIntermediates(), $this->london());

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_vo_type(): void
    {
        $itinerary = new ItineraryVO($this->paris(), $this->emptyIntermediates(), $this->london());

        $this->assertFalse($itinerary->equals($this->paris()));
    }

    // ========== IMMUTABILITY ==========

    public function test_immutability_after_computing_path(): void
    {
        $itinerary = new ItineraryVO(
            $this->paris(),
            $this->intermediates($this->lille()),
            $this->london(),
        );

        $itinerary->getOrderedPath();
        $itinerary->legs();
        $itinerary->totalDistance();
        $itinerary->summary();

        $this->assertSame(1, $itinerary->getIntermediates()->count());
        $this->assertSame(DistanceUnit::KILOMETRE, $itinerary->getUnit());
        $this->assertSame(ItineraryAlgorithm::GREEDY, $itinerary->getAlgorithm());
    }

    // ========== ALGORITHM ENUM CONTRACT ==========

    public function test_recommended_for_zero_returns_brute_force(): void
    {
        $this->assertSame(
            ItineraryAlgorithm::BRUTE_FORCE,
            ItineraryAlgorithm::recommendedFor(0),
        );
    }

    public function test_recommended_for_eight_returns_brute_force(): void
    {
        $this->assertSame(
            ItineraryAlgorithm::BRUTE_FORCE,
            ItineraryAlgorithm::recommendedFor(8),
        );
    }

    public function test_recommended_for_nine_returns_held_karp(): void
    {
        $this->assertSame(
            ItineraryAlgorithm::HELD_KARP,
            ItineraryAlgorithm::recommendedFor(9),
        );
    }

    public function test_recommended_for_fifteen_returns_held_karp(): void
    {
        $this->assertSame(
            ItineraryAlgorithm::HELD_KARP,
            ItineraryAlgorithm::recommendedFor(15),
        );
    }

    public function test_recommended_for_sixteen_returns_two_opt(): void
    {
        $this->assertSame(
            ItineraryAlgorithm::TWO_OPT,
            ItineraryAlgorithm::recommendedFor(16),
        );
    }

    public function test_recommended_for_thirty_returns_two_opt(): void
    {
        $this->assertSame(
            ItineraryAlgorithm::TWO_OPT,
            ItineraryAlgorithm::recommendedFor(30),
        );
    }

    public function test_supports_returns_true_up_to_max_intermediates(): void
    {
        $this->assertTrue(ItineraryAlgorithm::BRUTE_FORCE->supports(8));
        $this->assertTrue(ItineraryAlgorithm::HELD_KARP->supports(15));
        $this->assertTrue(ItineraryAlgorithm::GREEDY->supports(30));
        $this->assertTrue(ItineraryAlgorithm::TWO_OPT->supports(30));
    }

    public function test_supports_returns_false_above_max_intermediates(): void
    {
        $this->assertFalse(ItineraryAlgorithm::BRUTE_FORCE->supports(9));
        $this->assertFalse(ItineraryAlgorithm::HELD_KARP->supports(16));
        $this->assertFalse(ItineraryAlgorithm::GREEDY->supports(31));
        $this->assertFalse(ItineraryAlgorithm::TWO_OPT->supports(31));
    }
}
