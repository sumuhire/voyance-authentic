<?php

namespace App\Command;

use App\Entity\User;
use League\Csv\Reader;
use App\DTO\UserSearch;
use League\Csv\Statement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CsvImportCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    
    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->setDescription('Import a mock csv file')
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        /**
         * Title of command
         */
        $io->title('Data importation..');

        /**
         * Reader path
         */
        $reader = Reader::createFromPath('%kernel.root_dir%/../public/uploads/issuances/excels/test.csv', 'r');

        $reader->setHeaderOffset(0); //set the CSV header offset
        
        // $records = (new Statement())->process($reader);
        
        // $records = $csv->getRecords(['firstname','lastname','email']);

        // $results = explode(";", $records->getRecords());

        $records = $reader->getRecords(['firstname','lastname','email']);

        foreach($records as $offset => $record){

        $row = explode(';', $record['firstname']);

        $user = new User();

        $user->setFirstname($row[0]);
        $user->setLastname($row[1]);
        $user->setEmail($row[2]);

        //Verify email is not in database
        $email_compare2 = new UserSearch();
        $email_compare2->setSearch($row[2]);
        $findUser = $this->getDoctrine()->getManager()->getRepository(User::class)->findByEmail($email_compare2);
        $email_compare = new GraduateUserInviteSearch();
        $email_compare->setSearch($row[2]);
        $findGraduateUserInvite = $this->getDoctrine()->getManager()->getRepository(GraduateUserInvite::class)->findByEmail($email_compare);
       
            if(empty($findGraduateUserInvite) && empty($findUser)) {



                $this->em->persist($user);

            }

        }

        $this->em->flush();

        $io->success('tuto bene');
    }
}
