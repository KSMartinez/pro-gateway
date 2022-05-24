<?php

namespace App\Factory;

use App\Entity\Experience;
use App\Repository\ExperienceRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Experience>
 *
 * @method static Experience|Proxy createOne(array $attributes = [])
 * @method static Experience[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Experience|Proxy find(object|array|mixed $criteria)
 * @method static Experience|Proxy findOrCreate(array $attributes)
 * @method static Experience|Proxy first(string $sortedField = 'id')
 * @method static Experience|Proxy last(string $sortedField = 'id')
 * @method static Experience|Proxy random(array $attributes = [])
 * @method static Experience|Proxy randomOrCreate(array $attributes = [])
 * @method static Experience[]|Proxy[] all()
 * @method static Experience[]|Proxy[] findBy(array $attributes)
 * @method static Experience[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Experience[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ExperienceRepository|RepositoryProxy repository()
 * @method Experience|Proxy create(array|callable $attributes = [])
 */
final class ExperienceFactory extends ModelFactory
{
    /**
     *
     */
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
            'description' => self::faker()->text(),
            'jobname' => self::faker()->jobTitle(),
            'company' => self::faker()->company(),
            'category' => self::faker()->text(),
            'country' => self::faker()->country(),
            'city' => self::faker()->city(),
            'startMonth' => self::faker()->dateTime(),
            'startYear' => self::faker()->dateTime(),
            'endMonth' => self::faker()->dateTime(),
            'endYear' => self::faker()->dateTime(),
            'current_job' => self::faker()->text(),
            'cv' => CVFactory::random()
        ];
    }

    /**
     * @return $this
     */
    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Experience $experience): void {})
        ;
    }

    /**
     * @return string
     */
    protected static function getClass(): string
    {
        return Experience::class;
    }
}
