<?php

namespace App\Factory;

use App\Entity\Domain;
use App\Repository\DomainRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Domain>
 *
 * @method static Domain|Proxy createOne(array $attributes = [])
 * @method static Domain[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Domain|Proxy find(object|array|mixed $criteria)
 * @method static Domain|Proxy findOrCreate(array $attributes)
 * @method static Domain|Proxy first(string $sortedField = 'id')
 * @method static Domain|Proxy last(string $sortedField = 'id')
 * @method static Domain|Proxy random(array $attributes = [])
 * @method static Domain|Proxy randomOrCreate(array $attributes = [])
 * @method static Domain[]|Proxy[] all()
 * @method static Domain[]|Proxy[] findBy(array $attributes)
 * @method static Domain[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Domain[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static DomainRepository|RepositoryProxy repository()
 * @method Domain|Proxy create(array|callable $attributes = [])
 */
final class DomainFactory extends ModelFactory
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
            'name' => self::faker()->word(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Domain $domain): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Domain::class;
    }
}
