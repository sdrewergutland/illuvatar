<?php

namespace App\Tests\Library\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait EntityManagerAwareTestTrait
{
    abstract public static function getContainer(): ContainerInterface;

    protected static function getEntityManager(): EntityManagerInterface
    {
        return self::getContainer()->get('doctrine.orm.entity_manager');
    }

    protected static function clearEntityManager(): void
    {
        self::getEntityManager()->clear();
    }
}
