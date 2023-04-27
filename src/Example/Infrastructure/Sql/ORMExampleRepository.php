<?php

declare(strict_types=1);

namespace App\Example\Infrastructure\Sql;

use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleId;
use App\Example\Domain\Example\ExampleNotFoundException;
use App\Example\Domain\Example\ExampleRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Example>
 */
final class ORMExampleRepository extends ServiceEntityRepository implements ExampleRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Example::class);
    }

    public function save(Example $example): void
    {
        $this->getEntityManager()->persist($example);
        $this->getEntityManager()->flush();
    }

    public function mustFindOneById(ExampleId $id): Example
    {
        $example = $this->find($id->value());
        if (!$example instanceof Example) {
            throw ExampleNotFoundException::fromId($id);
        }

        return $example;
    }

    public function findOneById(ExampleId $id): ?Example
    {
        return $this->find($id->value());
    }
}
