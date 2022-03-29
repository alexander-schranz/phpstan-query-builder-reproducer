<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Visitor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * @phpstan-import-type VisitorRepositoryOneByFilter from VisitorRepositoryInterface
 * @phpstan-import-type VisitorRepositoryManyFilter from VisitorRepositoryInterface
 */
class VisitorRepository implements VisitorRepositoryInterface
{
    /**
     * @var EntityRepository<Visitor>
     */
    private EntityRepository $entityRepository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->entityRepository = $entityManager->getRepository(Visitor::class);
    }

    public function add(Visitor $visitor): void
    {
        $this->entityManager->persist($visitor);
    }

    public function remove(Visitor $visitor): void
    {
        $this->entityManager->remove($visitor);
    }

    public function findBy(array $filters): iterable
    {
        $queryBuilder = $this->createQueryBuilder($filters);

        $result = $queryBuilder->getQuery()->getResult();

        \PHPSTAN\dumpType($result);

        return $result;
    }

    public function findFlatBy(array $filters): iterable
    {
        $queryBuilder = $this->createQueryBuilder($filters);

        $queryBuilder->select('visitor.id')
            ->addSelect('visitor.title');

        $result = $queryBuilder->getQuery()->getResult();
        // $result = $queryBuilder->getQuery()->toIterable();

        \PHPSTAN\dumpType($result);

        return $result;
    }

    public function findOneBy(array $filters): ?Visitor
    {
        $queryBuilder = $this->createQueryBuilder($filters);

        try {
            $visitor = $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        }

        \PHPSTAN\dumpType($visitor);

        return $visitor;
    }

    public function getOneBy(array $filters): Visitor
    {
        $queryBuilder = $this->createQueryBuilder($filters);

        try {
            $visitor = $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException) {
            throw new \RuntimeException('Not found ...');
        }

        \PHPSTAN\dumpType($visitor);

        return $visitor;
    }

    /**
     * @param VisitorRepositoryOneByFilter|VisitorRepositoryManyFilter $filters
     */
    private function createQueryBuilder(array $filters): QueryBuilder
    {
        $queryBuilder = $this->entityRepository->createQueryBuilder('visitor');


        $id = $filters['id'] ?? null;
        if ($id) {
            $queryBuilder->andWhere('visitor.id = :id')
                ->setParameter('id', $id);
        }

        $ids = $filters['ids'] ?? null;
        if ($ids) {
            $queryBuilder->andWhere('visitor.id IN(:ids)')
                ->setParameter('ids', $id);
        }

        /*
        // here the result is correct visitor
        \PHPStan\dumpType($queryBuilder->getQuery()->getResult());
        */

        return $queryBuilder;
    }
}
