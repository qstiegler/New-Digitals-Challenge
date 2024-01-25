<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * The usual command doctrine:database:drop doesn't work with postgres as there is always a connection there for some
 * reason. Therefore, we use a custom one.
 */
#[AsCommand(name: 'app:reset-postgres')]
final class ResetPostgresDatabase extends Command
{
    private const string OPTION_INCLUDING_GRANT = 'including-grant';

    private readonly string $databaseName;

    public function __construct(
        private readonly Connection $connection,
        string $databaseUrl,
        private readonly bool $isPostgresResetAvailable,
    ) {
        parent::__construct();

        // Strip / from path and use it as database name
        $databasePath = parse_url($databaseUrl, PHP_URL_PATH);
        $this->databaseName = substr($databasePath, 1);
    }

    protected function configure(): void
    {
        parent::configure();

        $this->addOption(self::OPTION_INCLUDING_GRANT, null, null, 'Grant all on schema');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $io = new SymfonyStyle($input, $output);

        if (!$this->isPostgresResetAvailable) {
            $io->error('Postgres reset is not available, see IS_POSTGRES_RESET_AVAILABLE environment variable');

            return 1;
        }

        /** @var string $answer */
        $answer = $io->askQuestion(new Question('Do you really want to reset the database? (Y/n)', 'Y'));
        if ($answer !== 'Y') {
            $io->write("Do not reset database\n");

            return 0;
        }

        $sql = $input->getOption(self::OPTION_INCLUDING_GRANT)
            ? sprintf('
                DROP SCHEMA public CASCADE;
                CREATE SCHEMA public;
                GRANT ALL ON SCHEMA public TO %s;
                GRANT ALL ON SCHEMA public TO public;
            ', $this->databaseName)
            : '
                DROP SCHEMA public CASCADE;
                CREATE SCHEMA public;
            ';

        $this->connection->executeStatement($sql);

        $io->success('Drop successful');

        return 0;
    }
}
