<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension;

use Doctrine\DBAL\Connection;

final class SqlDatabaseInitializer
{
    private static bool $structureLoaded = false;

    public function __construct(
        private readonly Connection $connection,
        private readonly string $truncateSqlFile,
        private readonly string $structureSqlFile,
        private readonly string $dataSqlFile,
    ) {
        assert(file_exists($this->truncateSqlFile), $this->truncateSqlFile);
        assert(file_exists($this->structureSqlFile), $this->structureSqlFile);
        assert(file_exists($this->dataSqlFile), $this->dataSqlFile);
    }

    public function initDatabase(): void
    {
        if (true === $this->isLoadStructure()) {
            $this->loadStructure();
            self::$structureLoaded = true;
        }
        $this->clearTables();
        $this->loadData();
    }

    private function isLoadStructure(): bool
    {
        if (true === static::$structureLoaded) {
            return false;
        }

        $statement = $this->connection->prepare('SHOW TABLES;');
        $tables = $statement->executeQuery()->fetchAllAssociative();

        // for now we assume that a certain number of tables existing means that the structure is already there
        if (count($tables) > 50) {
            return false;
        }

        return true;
    }

    public function loadStructure(): void
    {
        list($result, $code) = $this->runMySQlCommandOnCommandLine(
            '< ' . $this->structureSqlFile,
        );

        if (0 !== $code) {
            throw new \RuntimeException(join(PHP_EOL, $result));
        }
    }

    public function clearTables(): void
    {
        list($result, $code) = $this->runMySQlCommandOnCommandLine(
            '< ' . $this->truncateSqlFile,
        );

        if (0 !== $code) {
            throw new \RuntimeException(join(PHP_EOL, $result));
        }
    }

    private function loadData(): void
    {
        list($result, $code) = $this->runMySQlCommandOnCommandLine(
            '--init-command="SET SESSION FOREIGN_KEY_CHECKS=0;"',
            '< ' . $this->dataSqlFile,
        );

        if (0 !== $code) {
            throw new \RuntimeException(join(PHP_EOL, $result));
        }
    }

    /**
     * @return array{0: array<int, string>, 1: int}
     */
    private function runMySQlCommandOnCommandLine(string ...$commandParts): array
    {
        /** @var array{user: string, password: string, host: string, dbname: string} $connectionParameters */
        $connectionParameters = $this->connection->getParams();

        $cmd = [
            'mysql',
            '-u' . $connectionParameters['user'],
            '-p' . $connectionParameters['password'],
            '-h' . $connectionParameters['host'],
            $connectionParameters['dbname'],
        ];

        $cmd = array_merge($cmd, $commandParts);
        $cmd = implode(' ', $cmd);

        $result = null;
        $code = null;
        exec($cmd, $result, $code);

        return [$result, $code];
    }
}
