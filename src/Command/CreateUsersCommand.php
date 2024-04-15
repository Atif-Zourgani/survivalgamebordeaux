<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class CreateUsersCommand extends Command
{
    protected static $defaultName = 'app:create-users';

    private $passwordEncoder;
    private $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates 100 new users.')
            ->setHelp('This command allows you to create 100 users...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 1; $i <= 100; $i++) {
            $user = new User();
            $user->setEmail("user${i}@gmail.com") 
                ->setUsername("user${i}")
                ->setPassword($this->passwordEncoder->hashPassword($user, 'password')) 
                ->setRoles(['ROLE_USER'])
                ->setAvatarUrl("user${i}"); 

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
        $output->writeln('100 new users were successfully created.');

        return Command::SUCCESS;
    }
}
