<?php

namespace Tsum\CashFlow\Console;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\MagentoCloud\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsum\CashFlow\Model\Import\Ones;

class OnesImportCli extends Command
{
    public const TYPE_ARGUMENT = 'type';

    // @todo add progress bar
    public function __construct(
        private readonly Ones $impoter,
        private readonly State $state
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('tsum:cf:import_data_from_ones')
            ->setDescription('Import from 1C')
            ->setDefinition([
                new InputArgument(
                    self::TYPE_ARGUMENT,
                    InputArgument::OPTIONAL,
                    'Import type. 0 - default, import incomes, 1 - import transfers'
                )
            ]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument(self::MODE_ARGUMENT) ? 'transfers' : 'incomes';
        $output->writeln("<info>Import of 1C {$type} is started</info>");
        try {
            $this->state->emulateAreaCode(Area::AREA_CRONTAB, function () use ($input) {
                $this->importer->importByType($input->getArgument(self::MODE_ARGUMENT));
            });

            $output->writeln('<info>Import of 1C {$type} was finished</info>');
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return Cli::FAILURE;
        }

        return Cli::SUCCESS;
    }
}
