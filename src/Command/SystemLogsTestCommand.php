<?php

namespace App\Command;

use App\Entity\SystemLogs;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function uniqid;

/**
 * Test system logger via command.
 *
 * @example ./bin/console test:systemlogs
 * @package App\Command
 */
class SystemLogsTestCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     * @return void
     * @throws InvalidArgumentException
     */
    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;

        parent::__construct();
    }

   /**
    * @return void
    * @throws InvalidArgumentException
    */
    protected function configure()
    {
        $this
            ->setName('test:systemlogs')
            ->setDescription('Test system logger')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('START: Executing command...');

        // 1. Test: Write
        $logItem = new SystemLogs();
        $logItem
            ->setChannel('TEST')
            ->setContent(uniqid('UID-', true));

        $this->em->persist($logItem);
        $this->em->flush();

        // 2. Test: Read
        /** @var $rep App\Repository\SystemLogsRepository */
        $rep   = $this->em->getRepository(SystemLogs::class);
        $item = $rep->getLast();

        $data = $rep->findByHashsum($item->getHashsum());

        // @todo WIP: Work with $data …

        $output->writeln('END');
    }
}
