<?php

namespace App\Command;

use App\Factory\SecretFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'add-secret',
    description: 'Add a short description for your command',
)]
class AddSecretCommand extends Command
{
    public function __construct(private EntityManagerInterface $manager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('secret', InputArgument::REQUIRED, 'This text will be saved as a secret')
            ->addArgument('expireAfterViews', InputArgument::REQUIRED, "The secret won't be available after the given number of views. It must be greater than 0.")
            ->addArgument('expireAfter', InputArgument::REQUIRED, "The secret won't be available after the given time. The value is provided in minutes. 0 means never expires.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $secret = $input->getArgument("secret");

        if ($secret) {
            $io->note(sprintf('Title: %s', $secret));
        }

        $expireAfterViews = $input->getArgument("expireAfterViews");

        if ($expireAfterViews) {
            $io->note(sprintf('Expires after: %s views', $expireAfterViews));
        }

        $expireAfter = $input->getArgument("expireAfter");

        if ($expireAfter) {
            $io->note(sprintf('Expires after: %s minutes', $expireAfter));
        }

        $entity = SecretFactory::createSecret($secret, $expireAfterViews, $expireAfter);
        $this->manager->persist($entity);
        $this->manager->flush();

        $io->success('Secret is saved: ' . json_encode($entity->asArray()));

        return Command::SUCCESS;
    }
}
