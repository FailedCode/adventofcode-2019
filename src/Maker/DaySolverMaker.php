<?php

namespace App\Maker;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Question\Question;

class DaySolverMaker extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:daysolver';
    }

    public function configureCommand(
        Command $command,
        InputConfiguration $inputConfig
    ) {
        $command
            ->setDescription('Creates a solver for a day')
            ->addArgument('day', InputArgument::OPTIONAL, 'What day is it for? (<fg=yellow>leave blank for today</>)')
        ;
        $inputConfig->setArgumentAsNonInteractive('day');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    /**
     * Interaction without validator to allow empty input
     *
     * @param InputInterface $input
     * @param ConsoleStyle $io
     * @param Command $command
     */
    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (null === $input->getArgument('day')) {
            $argument = $command->getDefinition()->getArgument('day');
            $question = new Question($argument->getDescription());
            $input->setArgument('day', $io->askQuestion($question));
        }
    }

    /**
     * @param InputInterface $input
     * @param ConsoleStyle $io
     * @param Generator $generator
     * @throws \Exception
     */
    public function generate(
        InputInterface $input,
        ConsoleStyle $io,
        Generator $generator
    ) {
        $day = (int)$input->getArgument('day');

        if (empty($day) || $day === 0) {
            $date = new \DateTime();
            $day = $date->format('d');
            $io->note("No day supplied, using $day");
        }

        $n = str_pad($day, 2, '0', STR_PAD_LEFT);

        $generator->generateFile(
            "src/Service/Day{$n}Solver.php",
            'src/Maker/templates/daysolver.tpl.php',
            [
                'n' => $n,
            ]
        );

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }
}
