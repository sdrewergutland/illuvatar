<?php

namespace App\Shared\Application\Command;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
abstract class AsyncCommandHandler implements CommandHandler
{
    public function throwExceptionWithNoRetry(\Throwable $e): never
    {
        throw new UnrecoverableMessageHandlingException($e->getMessage(), (int) $e->getCode(), $e);
    }

    public function throwExceptionWithIndefiniteRetry(\Throwable $e): never
    {
        throw new RecoverableMessageHandlingException($e->getMessage(), (int) $e->getCode(), $e);
    }
}
