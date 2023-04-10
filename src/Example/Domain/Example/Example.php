<?php

namespace App\Example\Domain\Example;

use App\Example\Infrastructure\Sql\Type\ExampleNameType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity, ORM\Table(name: 'example')]
class Example
{
    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private readonly Uuid $id;

    #[ORM\Column(type: ExampleNameType::TYPE, length: 255)]
    private ExampleName $name;

    public function __construct(Uuid $id, ExampleName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ExampleName
    {
        return $this->name;
    }
}
