<?php

declare(strict_types=1);

namespace App\CQRS\HandlerWrapper;

use DigitalCraftsman\CQRS\Command\Command;
use DigitalCraftsman\CQRS\HandlerWrapper\HandlerWrapperInterface;
use DigitalCraftsman\CQRS\Query\Query;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;

final readonly class ConnectionTransactionWrapper implements HandlerWrapperInterface
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    /** @param null $parameters */
    public function prepare(
        Command | Query $dto,
        Request $request,
        mixed $parameters,
    ): void {
        $this->connection->beginTransaction();
    }

    /** @param null $parameters */
    public function catch(
        Command | Query $dto,
        Request $request,
        mixed $parameters,
        \Exception $exception,
    ): ?\Exception {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }

        return $exception;
    }

    /** @param null $parameters */
    public function then(
        Command | Query $dto,
        Request $request,
        mixed $parameters,
    ): void {
        $this->connection->commit();
    }

    // Priorities

    public static function preparePriority(): int
    {
        return 50;
    }

    public static function catchPriority(): int
    {
        return 50;
    }

    public static function thenPriority(): int
    {
        return 50;
    }

    /** @param null $parameters */
    public static function areParametersValid(mixed $parameters): bool
    {
        return $parameters === null;
    }
}
