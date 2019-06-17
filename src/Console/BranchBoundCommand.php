<?php


namespace Panda\Tsp\Console;


use Panda\Tsp\BranchBound;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class BranchBoundCommand extends Command
{
    /** @var BranchBound */
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = BranchBound::getInstance('Branch-n-bound');
    }

    protected function configure()
    {
        $this->setName('tsp-solve')
            ->setDescription('Solve Travellers Salesman Problem with Branch and Bound method');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $locationNameQuestion = new Question("Please enter location name: ");
        $locationNameQuestion->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });
        $latitudeQuestion = new Question("Please enter latitude (Only numbers between -90 and 90): ");

        $latitudeQuestion->setValidator(function($value) {
            if (!is_numeric($value) || ($value > 90 || $value < -90)) {
                throw new \InvalidArgumentException("Please! Provide valid number");
            }
        });
        $latitudeQuestion->setMaxAttempts(3);

        $longitudeQuestion = new Question("Please enter longitude: (Only numbers between -180 and 180)");
        $longitudeQuestion->setValidator(function($value) {
            if (!is_numeric($value) || ($value > 180 || $value < -180)) {
                throw new \InvalidArgumentException("Please! Provide valid number");
            }
        });
        $latitudeQuestion->setMaxAttempts(3);

        $exitQuestion = new ConfirmationQuestion("<question>Enough? </question>", false);

        $output->writeln("<info>
                              _______  _____  _____                                               
                             |__   __|/ ____||  __ \                                              
                                | |  | (___  | |__) |                                             
                                | |   \___ \ |  ___/                                              
                                | |   ____) || |                                                  
                                |_|  |_____/ |_|                                                  
  ____                            _                         _   ____                            _ 
 |  _ \                          | |                       | | |  _ \                          | |
 | |_) | _ __  __ _  _ __    ___ | |__     __ _  _ __    __| | | |_) |  ___   _   _  _ __    __| |
 |  _ < | '__|/ _` || '_ \  / __|| '_ \   / _` || '_ \  / _` | |  _ <  / _ \ | | | || '_ \  / _` |
 | |_) || |  | (_| || | | || (__ | | | | | (_| || | | || (_| | | |_) || (_) || |_| || | | || (_| |
 |____/ |_|   \__,_||_| |_| \___||_| |_|  \__,_||_| |_| \__,_| |____/  \___/  \__,_||_| |_| \__,_|
                                                                                                  
                                                                                                  </info>");

        do {
            $id = $helper->ask($input, $output, $locationNameQuestion);
            $latitude = $helper->ask($input, $output, $latitudeQuestion);
            $longitude = $helper->ask($input, $output, $longitudeQuestion);

            $this->service->addLocation([
                'id' => $id,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);

        } while (!$helper->ask($input, $output, $exitQuestion));

        $ans = $this->service->solve();
        $totalCost = ceil($ans['cost']);
        $output->writeln("Total cost: {$totalCost}");

    }
}