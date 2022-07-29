<?php

namespace App\Factory;

use DateTime;
use App\Entity\News;
use App\Entity\NewsStatus;
use App\Entity\NewsCategory;
use Zenstruck\Foundry\Proxy;
use App\Repository\NewsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\RepositoryProxy;
use function Zenstruck\Foundry\factory;

/**
 * @extends ModelFactory<News>
 *
 * @method static News|Proxy createOne(array $attributes = [])
 * @method static News[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static News|Proxy find(object|array|mixed $criteria)
 * @method static News|Proxy findOrCreate(array $attributes)
 * @method static News|Proxy first(string $sortedField = 'id')
 * @method static News|Proxy last(string $sortedField = 'id')
 * @method static News|Proxy random(array $attributes = [])
 * @method static News|Proxy randomOrCreate(array $attributes = [])
 * @method static News[]|Proxy[] all()
 * @method static News[]|Proxy[] findBy(array $attributes)
 * @method static News[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static News[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static NewsRepository|RepositoryProxy repository()
 * @method News|Proxy create(array|callable $attributes = [])
 */
final class NewsFactory extends ModelFactory
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
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => self::faker()->text(50),
            'description' => self::faker()->paragraphs(3, true),
            'forAllUniversities' => self::faker()->boolean(),
            'university' => self::faker()->company(),
            'category' => factory(NewsCategory::class)->random(),
            'publishedAt' => DateTime::createFromInterface(self::faker()->dateTime()),
            'imageUrl' => self::faker()->imageUrl(),
            'visibility' => 'private',
            'chapo' => self::faker()->paragraphs(3, true),
            'newsStatus' => factory(NewsStatus::class)->random(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(News $news): void {})
        ;
    }

    protected static function getClass(): string
    {
        return News::class;
    }
}
