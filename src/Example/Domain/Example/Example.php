<?php

namespace App\Example\Domain\Example;

use App\Example\Domain\Example\Event\ExampleCreatedEvent;
use App\Example\Infrastructure\Sql\Type\ExampleNameType;
use App\Shared\Domain\DomainEventAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity, ORM\Table(name: 'example')]
class Example
{
    use DomainEventAwareTrait;

    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private readonly Uuid $id;

    #[ORM\Column(type: ExampleNameType::TYPE, length: 255)]
    private ExampleName $name;

    private function __construct(ExampleId $id, ExampleName $name)
    {
        $this->id = $id->value();
        $this->name = $name;
    }

    public static function create(ExampleId $id, ExampleName $name): self
    {
        $self = new self($id, $name);
        $self->recordDomainEvent(new ExampleCreatedEvent($id, $name));

        return $self;
    }

    public function getId(): ExampleId
    {
        return ExampleId::fromUuid($this->id);
    }

    public function getName(): ExampleName
    {
        return $this->name;
    }
}
