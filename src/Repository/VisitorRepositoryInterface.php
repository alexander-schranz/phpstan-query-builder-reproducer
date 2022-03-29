<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Visitor;

/**
 * @phpstan-type VisitorRepositoryManyFilter array{
 *     ids?: int,
 * }
 * @phpstan-type VisitorRepositoryOneByFilter array{
 *     id?: int,
 * }
 */
interface VisitorRepositoryInterface
{
    public function add(Visitor $visitor): void;

    public function remove(Visitor $visitor): void;

    /**
     * @param VisitorRepositoryManyFilter $filters
     *
     * @return iterable<Visitor>
     */
    public function findBy(array $filters): iterable;

    /**
     * @param VisitorRepositoryManyFilter $filters
     *
     * @return iterable<array{
     *     id: int,
     *     title: string|null,
     * }>
     */
    public function findFlatBy(array $filters): iterable;

    /**
     * @param VisitorRepositoryOneByFilter $filters
     */
    public function findOneBy(array $filters): ?Visitor;

    /**
     * @param VisitorRepositoryOneByFilter $filters
     */
    public function getOneBy(array $filters): Visitor;
}
