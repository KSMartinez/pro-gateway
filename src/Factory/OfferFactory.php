<?php

namespace App\Factory;

use App\Entity\Domain;
use App\Entity\LevelOfEducation;
use App\Entity\Offer;
use App\Entity\OfferStatus;
use App\Entity\SectorOfOffer;
use App\Entity\TypeOfContract;
use App\Entity\TypeOfOffer;
use App\Repository\OfferRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use function Zenstruck\Foundry\factory;

/**
 * @extends ModelFactory<Offer>
 *
 * @method static Offer|Proxy createOne(array $attributes = [])
 * @method static Offer[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Offer|Proxy find(object|array|mixed $criteria)
 * @method static Offer|Proxy findOrCreate(array $attributes)
 * @method static Offer|Proxy first(string $sortedField = 'id')
 * @method static Offer|Proxy last(string $sortedField = 'id')
 * @method static Offer|Proxy random(array $attributes = [])
 * @method static Offer|Proxy randomOrCreate(array $attributes = [])
 * @method static Offer[]|Proxy[] all()
 * @method static Offer[]|Proxy[] findBy(array $attributes)
 * @method static Offer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Offer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static OfferRepository|RepositoryProxy repository()
 * @method Offer|Proxy create(array|callable $attributes = [])
 */
final class OfferFactory extends ModelFactory
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
            'title' => self::faker()->text(),
            'description' => self::faker()->text(self::faker()->numberBetween(50,100)),
            'city' => self::faker()->city(),
            'country' => self::faker()->country(),
            'datePosted' => self::faker()->dateTime(),
            'publishDuration' => self::faker()->randomNumber(),
            'domain' => factory(Domain::class)->random(),
            'typeOfContract' => factory(TypeOfContract::class)->random(),
            'minSalary' => self::faker()->numberBetween(10000, 30000),
            'maxSalary' => self::faker()->numberBetween(10000, 30000),
            'companyName' => self::faker()->company(),
            'isDirect' => self::faker()->boolean(),
            'isPublic' => self::faker()->boolean(),
            'isOfPartner' => self::faker()->boolean(),
            'offerId' => self::faker()->unique()->numberBetween(0,20),
            'offerStatus' => factory(OfferStatus::class)->random(),
            'typeOfOffer' => factory(TypeOfOffer::class)->random(),
            'sector' => factory(SectorOfOffer::class)->random(),
            'levelOfEducation' => factory(LevelOfEducation::class)->random()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Offer $offer): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Offer::class;
    }
}
