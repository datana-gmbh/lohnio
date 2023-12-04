<?php

declare(strict_types=1);

namespace App\Bridge\Doctrine\ORM\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T of object
 *
 * @template-extends ObjectRepository<T>
 */
interface EntityRepositoryInterface extends ObjectRepository
{
    /**
     * @param string      $alias
     * @param null|string $indexBy
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias, $indexBy = null);

    /**
     * Counts entities by a set of criteria.
     *
     * @param array<string, mixed> $criteria
     *
     * @return int the cardinality of the objects that match the given criteria
     */
    public function count(array $criteria);
}
