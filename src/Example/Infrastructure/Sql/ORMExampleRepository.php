<?php

declare(strict_types=1);

namespace App\Example\Infrastructure\Sql;

use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleNotFoundException;
use App\Example\Domain\Example\ExampleRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Example>
 */
class ORMExampleRepository extends ServiceEntityRepository implements ExampleRepository
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

    public function mustFindOneById(Uuid $id): Example
    {
        $example = $this->find($id);
        if (!$example instanceof Example) {
            throw ExampleNotFoundException::fromId($id);
        }

        return $example;
    }
}
