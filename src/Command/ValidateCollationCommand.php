<?php

declare(strict_types=1);

namespace App\Command;

use App\Doctrine\ColumnOptions;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:validate-collation',
    description: 'Validates that all varchar and text columns have collation set correctly',
)]
final class ValidateCollationCommand extends Command
{
    public function __construct(
        private readonly Connection $connection,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $columnsWithoutCollation = $this->getColumnsWithoutCollation();

        if (!empty($columnsWithoutCollation)) {
            $io->error('Columns without collation found.');
            $io->writeln('Run the following queries:');

            $errors = [];
            foreach ($columnsWithoutCollation as $columnWithoutCollation) {
                $errors[] = sprintf('%s - %s', $columnWithoutCollation['table_name'], $columnWithoutCollation['column_name']);
            }

            $errorMessage = sprintf("The following columns do not have the collation explicitly set to '%s':\n", ColumnOptions::DEFAULT_COLLATION['collation']);
            $errorMessage = sprintf('%s%s', $errorMessage, implode("\n", $errors));

            $io->write($errorMessage);

            return self::FAILURE;
        }

        $io->success('Collation validation successful');

        return self::SUCCESS;
    }

    /**
     * @return array<int, array{
     *     table_name: string,
     *     column_name: string,
     * }>
     */
    private function getColumnsWithoutCollation(): array
    {
        $sql = '
            SELECT c.table_name, c.column_name
            FROM information_schema.columns c
            INNER JOIN information_schema.tables t
                ON t.table_name = c.table_name
            WHERE c.table_schema = \'public\'
                AND t.table_type = \'BASE TABLE\'
                AND c.column_name != \'discriminator\'
                AND c.table_name != \'lock_keys\'
                AND c.table_name != \'migration_versions\'
                AND c.table_name != \'cache_items\'
                AND c.column_name != \'month\'
                AND (
                    c.data_type = \'text\'
                    OR c.data_type = \'character varying\'
                )
                AND (
                    c.collation_name is null
                    OR c.collation_name != \'de-DE-x-icu\'
                );
        ';

        /**
         * @var array<int, array{
         *     table_name: string,
         *     column_name: string,
         * }>
         */
        return $this->connection->fetchAllAssociative($sql);
    }
}
