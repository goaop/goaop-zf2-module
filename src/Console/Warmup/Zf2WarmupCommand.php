<?php

namespace Go\ZF2\GoAopModule\Console\Warmup;

use Go\Core\AspectKernel;
use Go\Instrument\ClassLoading\SourceTransformingLoader;
use Go\Instrument\FileSystem\Enumerator;
use Go\Instrument\Transformer\FilterInjectorTransformer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Zf2WarmupCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('goaop:warmup')
            ->addArgument(
                'applicationConfig',
                InputArgument::REQUIRED,
                'Path to application config which includes aspects and goaop module'
            )
            ->setDescription('Warm up the cache with woven aspects')
            ->setHelp(<<<EOT
Initializes the kernel and, if successful, warm up the cache for PHP
files under the application directory.

By default, the cache directory is taken from configured AspectKernel class.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start up application with supplied config...');
        $config = $input->getArgument('applicationConfig');
        $path   = stream_resolve_include_path($config);
        if (!is_readable($path)) {
            throw new \InvalidArgumentException("Invalid loader path: {$config}");
        }

        // Init the application once using given config
        // This way the late static binding on the AspectKernel
        // will be on the goaop-zf2-module kernel
        \Zend\Mvc\Application::init(include $path);

        if (!class_exists(AspectKernel::class, false)) {
            $message = "Kernel was not initialized yet. Maybe missing module Go\ZF2\GoAopModule in config {$path}";
            throw new \InvalidArgumentException($message);
        }

        $kernel  = AspectKernel::getInstance();
        $options = $kernel->getOptions();

        if (empty($options['cacheDir'])) {
            throw new \InvalidArgumentException('Cache warmer require the `cacheDir` options to be configured');
        }

        $enumerator = new Enumerator($options['appDir'], $options['includePaths'], $options['excludePaths']);
        $iterator   = $enumerator->enumerate();

        $totalFiles = iterator_count($iterator);
        $output->writeln("Total <info>{$totalFiles}</info> files to process.");
        $iterator->rewind();

        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
        });

        $index  = 0;
        $errors = [];
        foreach ($iterator as $file) {
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln("Processing file <info>{$file->getRealPath()}</info>");
            }
            $isSuccess = null;
            try {
                // This will trigger creation of cache
                file_get_contents(
                    FilterInjectorTransformer::PHP_FILTER_READ .
                    SourceTransformingLoader::FILTER_IDENTIFIER .
                    '/resource=' . $file->getRealPath()
                );
                $isSuccess = true;
            } catch (\Exception $e) {
                $isSuccess = false;
                $errors[$file->getRealPath()] = $e;
            }
            if ($output->getVerbosity() == OutputInterface::VERBOSITY_NORMAL) {
                $output->write($isSuccess ? '.' : '<error>E</error>');
                if (++$index % 50 == 0) {
                    $output->writeln("($index/$totalFiles)");
                }
            }
        }

        restore_error_handler();

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
            foreach ($errors as $file=>$error) {
                $message = "File {$file} is not processed correctly due to exception: {$error->getMessage()}";
                $output->writeln($message);
            }
        }

        $output->writeln('<info>Done</info>');
    }

}
