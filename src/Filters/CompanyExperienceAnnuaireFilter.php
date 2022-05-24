<?php

namespace App\Filters;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

/**
 *
 */
class CompanyExperienceAnnuaireFilter extends AbstractFilter
{

    /**
     * @param string                      $property
     * @param mixed                       $value
     * @param QueryBuilder                $queryBuilder
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string                      $resourceClass
     * @param string|null                 $operationName
     * @return void
     */
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($property !== 'enterprise') {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->innerJoin(sprintf('%s.cV', $alias), 'cv')
            ->innerJoin('cv.experiences', 'exp')
            ->andWhere('exp.company LIKE :company')
            ->setParameter(':company', '%' . $value . '%');




    }

    /**
     * @param string $resourceClass
     * @return array<mixed>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            'enterprise' => [
                'property' => null,
                'type'=> 'string',
                'required' => false
            ]
        ];
    }
}