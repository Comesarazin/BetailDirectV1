<?php
namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommand extends Command
{
	protected static $defaultName = 'app:create-user';
	private $entityManager;
	private $passwordHasher;

	public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
	{
		parent::__construct();
		$this->entityManager = $entityManager;
		$this->passwordHasher = $passwordHasher;
	}

	protected function configure(): void
	{
		$this
			->setDescription('Creates a new user with the role ROLE_ADMIN.')
			->addArgument('email', InputArgument::REQUIRED, 'The email of the new user.')
			->addArgument('password', InputArgument::REQUIRED, 'The password of the new user.');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		$email = $input->getArgument('email');
		$password = $input->getArgument('password');

		$user = new User();
		$user->setEmail($email);
		$user->setRoles(['ROLE_ADMIN']);
		$hashedPassword = $this->passwordHasher->hashPassword($user, $password);
		$user->setPassword($hashedPassword);

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		$io->success(sprintf('User %s was successfully created with role ROLE_ADMIN.', $email));

		return Command::SUCCESS;
	}
}