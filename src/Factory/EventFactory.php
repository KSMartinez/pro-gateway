<?php

namespace App\Factory;

use App\Entity\Event;
use App\Entity\EventCategory;
use App\Repository\EventRepository;
use DateTimeImmutable;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use function Zenstruck\Foundry\factory;

/**
 * @extends ModelFactory<Event>
 *
 * @method static Event|Proxy createOne(array $attributes = [])
 * @method static Event[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Event|Proxy find(object|array|mixed $criteria)
 * @method static Event|Proxy findOrCreate(array $attributes)
 * @method static Event|Proxy first(string $sortedField = 'id')
 * @method static Event|Proxy last(string $sortedField = 'id')
 * @method static Event|Proxy random(array $attributes = [])
 * @method static Event|Proxy randomOrCreate(array $attributes = [])
 * @method static Event[]|Proxy[] all()
 * @method static Event[]|Proxy[] findBy(array $attributes)
 * @method static Event[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Event[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static EventRepository|RepositoryProxy repository()
 * @method Event|Proxy create(array|callable $attributes = [])
 */
final class EventFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    /**
     * @return array<mixed>
     */
    protected function getDefaults(): array
    {
        $start = self::faker()->dateTimeBetween('-3 months', '+3 months');
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => self::faker()->text(50),
            'description' => self::faker()->paragraphs(3, true),
            'forAllUniversities' => self::faker()->boolean(),
            'university' => self::faker()->company(),
            'isPublic' => self::faker()->boolean(),
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'company' => self::faker()->company(),
            'maxNumberOfParticipants' => self::faker()->randomNumber(),
            'startingAt' => $start,
            'endingAt' => self::faker()->dateTimeBetween($start, $start->format('Y-m-d H:i:s').'+1 months'),
            'location' => self::faker()->city(),
            'category' => factory(EventCategory::class)->random(),
            'image' => self::faker()->imageUrl(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Event $event): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Event::class;
    }
}
