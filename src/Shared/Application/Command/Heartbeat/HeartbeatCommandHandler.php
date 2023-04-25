<?php

namespace App\Shared\Application\Command\Heartbeat;

use App\Shared\Application\Command\AsyncCommandHandler;

class HeartbeatCommandHandler extends AsyncCommandHandler
{
    public function __invoke(HeartbeatCommand $command): void
    {
        $command::$isHandled = true;
    }
}
