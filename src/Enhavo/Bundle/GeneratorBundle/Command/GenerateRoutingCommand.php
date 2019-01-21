<?php
/**
 * GenerateRoutingCommand.php
 *
 * @since 28/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GeneratorBundle\Command;

use Enhavo\Bundle\GeneratorBundle\Generator\RoutingGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRoutingCommand extends Command
{
    /**
     * @var RoutingGenerator
     */
    private $generator;

    /**
     * GenerateRoutingCommand constructor.
     * @param RoutingGenerator $generator
     */
    public function __construct(RoutingGenerator $generator)
    {
        $this->generator = $generator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:generate:routing')
            ->setDescription('Create default routing')
            ->addArgument(
                'app',
                InputArgument::REQUIRED,
                'What is the app name?'
            )
            ->addArgument(
                'resource',
                InputArgument::REQUIRED,
                'What is the name of the resource?'
            )
            ->addOption(
                'sorting',
                null,
                InputOption::VALUE_REQUIRED,
                'If the resource can be sorted, what is the property name to sort by?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $input->getArgument('app');
        $resource = $input->getArgument('resource');
        $sorting = $input->getOption('sorting');
        $outputCode = $this->generator->generate($app, $resource, $sorting);
        $output->writeln($outputCode);
    }
}
