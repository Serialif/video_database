<?php

namespace App\Command;

use DateTime;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCommand extends Command
{
    protected static $defaultName = 'sass:pattern';

    private const STYLES_PATH = 'assets/styles/';
    private const CHOICE_ALL_FILES_SAFE = 'add all files checking if they already exist';
    private const CHOICE_ALL_FILES_FORCE = 'add all files even if they exist';
    private const CHOICE_STOP_ADDING_FILES = 'stop adding files';

    private const DIRECTORIES = ['utils', 'bases', 'components', 'layouts', 'pages', 'themes', 'vendors'];

    private const BASIC_FILES = [
        ['file' => 'reset.scss', 'directory' => 'bases'],
        ['file' => 'typography.scss', 'directory' => 'bases'],
        ['file' => 'bases.scss', 'directory' => 'bases'],
        ['file' => 'alerts.scss', 'directory' => 'components'],
        ['file' => 'buttons.scss', 'directory' => 'components'],
        ['file' => 'checkboxes.scss', 'directory' => 'components'],
        ['file' => 'toggle_switches.scss', 'directory' => 'components'],
        ['file' => 'mixins.scss', 'directory' => 'utils'],
        ['file' => 'variables.scss', 'directory' => 'utils']
    ];

    private const MESSAGES_TYPE = [
        'success' => ['color' => 'green', 'title' => '[SUCCESS]'],
        'warning' => ['color' => 'yellow', 'title' => '[WARNING]'],
        'error' => ['color' => 'red', 'title' => '[ERROR]'],
    ];

    /**
     * Data to write to SCSS files
     * @var array
     */
    private array $scssData = [];

    /**
     * Path to SCSS files
     * @var string
     */
    private string $path;

    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    /**
     * UserCommand constructor.
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);
        foreach (self::BASIC_FILES as $basicFile) {
            $file = $basicFile['file'];
            $directory = $basicFile['directory'];
            $key = "$file ($directory)";
            $this->scssData[$key] = file_get_contents("src/command/ScssTemplate/$directory/_$file");
        }
    }

    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates SCSS 7-1 pattern structure.')

            // the full command description shown when running the command with the "--help" option
            ->setHelp(
                "This command allows you to create SCSS 7-1 pattern structure: <fg=magenta>directories</> " .
                "('bases', 'utils', 'layouts', 'components', 'pages', 'themes', 'vendors') and " .
                "<fg=magenta>scss files</> (reset, typography, bases, alerts, buttons, checkboxes, " .
                "toggle_switches, mixins, variables). Also adds their import to app.scss"
            )
            ->addArgument('path', InputArgument::OPTIONAL, 'SCSS 7-1 pattern structure path (optional)')
            ->addOption('file', ['-f'], InputOption::VALUE_NONE, 'Only files management')
            ->addOption('directory', ['-d'], InputOption::VALUE_NONE, 'Only directories management')
            ->addOption('import', ['-i'], InputOption::VALUE_NONE, 'Only imports management');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        try {
            if ($input->getOption('no-interaction')) {
                $this->path = self::STYLES_PATH;
                $this->manageDirectories($output);
                $this->writeTitle($output, 'Files');
                $this->manageFiles($output, true);
                $this->manageImports($output, true);
            } elseif ($input->getOption('file')) {
                $this->path = self::STYLES_PATH;
                $this->writeTitle($output, 'Files');
                $this->manageFiles($output, true);
            } elseif ($input->getOption('directory')) {
                $this->path = self::STYLES_PATH;
                $this->manageDirectories($output);
            } elseif ($input->getOption('import')) {
                $this->path = self::STYLES_PATH;
                $this->manageImports($output, true, true);
            } else {
                if ($input->getArgument('path') === null) {
                    $this->path = $this->io->ask('Define pattern path:', self::STYLES_PATH);
                } else {
                    $this->path = $input->getArgument('path');
                }

                $this->path = substr(
                    $this->path,
                    strlen($this->path) - 1
                ) !== '/' ? $this->path . '/' : $this->path;

                $this->manageDirectories($output);

                $this->writeTitle($output, 'Files');
                if ($this->io->confirm('Do you want to create/overwrite basic files?', true)) {
                    $this->manageFiles($output);
                }
                $this->manageImports($output);
            }

            $this->writeTitle($output, 'Result');

            $this->io->success("SCSS 7-1 pattern structure have been updated");
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->writeTitle($output, 'Result');

            $this->writeMessage($output, $e->getMessage(), true, 'error');
            $output->writeln('');
            $this->io->error("SCSS 7-1 pattern structure haven't been updated");
            return Command::FAILURE;
        }
    }

    /**
     * Files management
     *
     * @param OutputInterface $output
     * @param bool|null $force
     */
    protected function manageFiles(OutputInterface $output, ?bool $force = false): void
    {
        $fileToAdd = '';

        while ($fileToAdd !== self::CHOICE_STOP_ADDING_FILES) {
            $basicFiles = [];
            foreach (self::BASIC_FILES as $basicFile) {
                $file = $basicFile['file'];
                $directory = $basicFile['directory'];
                $tmpFile = "$file ($directory)";
                if (file_exists("$this->path$directory/_$file")) {
                    $basicFiles[] = '* ' . $tmpFile;
                } else {
                    $basicFiles[] = $tmpFile;
                }
            }

            if (!$force) {
                $basicFiles[] = self::CHOICE_ALL_FILES_SAFE;
                $basicFiles[] = self::CHOICE_ALL_FILES_FORCE;
                $basicFiles[] = self::CHOICE_STOP_ADDING_FILES;

                $fileToAdd = $this->io->choice(
                    'Files to add (files marked with * already exists, they can be overwritten)',
                    $basicFiles,
                    self::CHOICE_ALL_FILES_SAFE
                );
            } else {
                for ($j = 0; $j < 9; $j++) {
                    $this->manageOneFile($basicFiles[$j], $output, true);
                }
                $output->writeln('');
                break;
            }


            if ($fileToAdd === '' . self::CHOICE_ALL_FILES_SAFE . '') {
                for ($j = 0; $j < 9; $j++) {
                    $this->manageOneFile($basicFiles[$j], $output);
                }
                break;
            } elseif ($fileToAdd === self::CHOICE_ALL_FILES_FORCE) {
                if ($this->io->confirm('Are you sure (all existing files will be overwritten)?', true)) {
                    for ($j = 0; $j < 9; $j++) {
                        $this->manageOneFile($basicFiles[$j], $output, true);
                    }
                    $output->writeln('');
                    break;
                }
            } elseif ($fileToAdd === self::CHOICE_STOP_ADDING_FILES) {
                $this->writeMessage($output, 'Adding files stopped by user', true, 'warning');
            } else {
                $this->manageOneFile($fileToAdd, $output);
            }

            $output->writeln('');
        }
    }

    /**
     * Create or overwrite a file
     *
     * @param string $fileToAdd
     * @param OutputInterface $output
     * @param bool|null $force
     */
    private function manageOneFile(string $fileToAdd, OutputInterface $output, ?bool $force = false)
    {
        $fileToAdd = ltrim($fileToAdd, '* ');

        $data = $this->scssData[$fileToAdd];

        $fileToAdd = rtrim($fileToAdd, ')');

        $position = strpos($fileToAdd, '(');
        $file = substr($fileToAdd, 0, $position - 1);
        $filePath = substr($fileToAdd, $position + 1);

        $fileFullPath = $this->path . $filePath . '/_' . $file;

        if (file_exists($fileFullPath)) {
            if ($force) {
                file_put_contents($fileFullPath, $data);
            } else {
                $this->writeMessage($output, "$fileFullPath already exists", false, 'warning');
                if ($this->io->confirm('Overwrite this file?', false)) {
                    file_put_contents($fileFullPath, $data);
                }
            }
            $this->writeMessage($output, "overwritten: $fileFullPath", true, 'warning');
        } else {
            file_put_contents($fileFullPath, $data);
            $this->writeMessage($output, "added: $fileFullPath");
        }
    }

    /**
     * Directories management
     *
     * @param OutputInterface $output
     */
    private function manageDirectories(OutputInterface $output): void
    {
        $successExists = false;
        $warningExists = false;
        $successDirectories = [];
        $warningDirectories = [];

        foreach (self::DIRECTORIES as $directory) {
            if (file_exists($this->path . $directory)) {
                $warningExists = true;
                $warningDirectories[] = $this->path . $directory;
            } else {
                mkdir($this->path . $directory, 0777, true);
                $successExists = true;
                $successDirectories[] = $this->path . $directory;
            }
        }

        $this->writeTitle($output, 'Directories');

        if ($successExists) {
            $this->writeMessage($output, 'These directories have been created:');
            foreach ($successDirectories as $successDirectory) {
                $this->writeMessage($output, "  * $successDirectory", false);
            }
            $output->writeln('');
        }

        if ($warningExists) {
            $this->writeMessage(
                $output,
                'Some directories already exist, they weren\'t be overwritten:',
                'true',
                'warning'
            );
            foreach ($warningDirectories as $warningDirectory) {
                $this->writeMessage($output, "  * $warningDirectory", false, 'warning');
            }
            $output->writeln('');
        }
    }

    /**
     * Adds import to main scss file
     *
     * @param OutputInterface $output
     * @param bool|null $force
     * @param bool|null $onlyImports
     */
    private function manageImports(OutputInterface $output, ?bool $force = false, ?bool $onlyImports = false)
    {
        $this->writeTitle($output, 'Imports');

        if ($force || $onlyImports) {
            $appFile = $this->path . 'app.scss';
        } else {
            if ($this->io->confirm('Do you want to manage scss imports?', true)) {
                $appFile = $this->io->ask(
                    'Define the main scss file where to add the imports:',
                    $this->path . 'app.scss'
                );
            } else {
                $appFile = null;
            }
        }

        if ($appFile !== null) {
            $fileContents = file_get_contents($appFile);
            $imports = '';

            $basicFiles = $this->getAllFilesAndPaths($this->path);

            $basicFiles = $this->sortFiles($basicFiles);

            foreach ($basicFiles as $file => $directory) {
                $file = str_replace('.scss', '', ltrim($file, '_'));
                $import = 'import "' . $directory . '/' . $file . '";';
                if (!stripos($fileContents, $import)) {
                    $imports .= PHP_EOL . '@' . $import;
                }
            }

            if ($imports !== '') {
                $date = (new DateTime())->format('Y-m-d H:i:s');
                file_put_contents(
                    $appFile,
                    PHP_EOL . PHP_EOL . '//' . $date . ' Auto added by command "symfony console sass:pattern"' .
                    $imports,
                    file_exists($appFile) ? FILE_APPEND : null
                );
                $this->writeMessage($output, "All @import successfully added to $appFile");
            } else {
                $this->writeMessage($output, "All @import were already in $appFile", true, 'warning');
            }
        }
    }

    /**
     * Writes a underscored title to the output and adds a newline before, at the end and after.
     *
     * @param OutputInterface $output
     * @param string $message
     */
    private function writeTitle(OutputInterface $output, string $message): void
    {
        $output->writeln('');
        $output->writeln("<fg=blue;options=underscore>$message</>");
        $output->writeln('');
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param OutputInterface $output
     * @param string $message
     * @param bool|null $showTitle
     * @param string|null $type
     */
    private function writeMessage(
        OutputInterface $output,
        string $message,
        ?bool $showTitle = true,
        ?string $type = 'success'
    ): void {
        $title = $showTitle ? self::MESSAGES_TYPE[$type]['title'] : '';

        $color = self::MESSAGES_TYPE[$type]['color'];

        $output->writeln("<fg=black;bg=$color>$title</> $message");
    }

    /**
     * Sort files according to their folder and self::DIRECTORIES
     *
     * @param array $filesAndPaths
     * @return array
     */
    public function sortFiles(array $filesAndPaths): array
    {
        $sortedFilesAndPaths = [];

        foreach (self::DIRECTORIES as $directory) {
            foreach ($filesAndPaths as $file => $dir) {
                if ($directory === $dir) {
                    $sortedFilesAndPaths[$file] = $dir;
                }
            }
        }

        return $sortedFilesAndPaths;
    }

    /**
     * Return all files in a folder recursively with their path
     *
     * @param string $directory
     * @return array
     */
    public function getAllFilesAndPaths(string $directory): array
    {
        $filesAndPaths = [];
        $allFiles = $this->getAllPathsFiles($directory);

        foreach ($allFiles as $oneFile) {
            $filePath = str_replace($directory, '', $oneFile);
            $lastSlashPosition = strrpos($filePath, '/');
            if ($lastSlashPosition !== false) {
                $file = substr($filePath, $lastSlashPosition + 1);
                $folder = substr($filePath, 0, $lastSlashPosition);
                $filesAndPaths[$file] = $folder;
            }
        }

        return $filesAndPaths;
    }

    /**
     * Return all paths of files in a folder recursively
     *
     * @param string $directory
     * @return array
     */
    public function getAllPathsFiles(string $directory): array
    {
        $directory = rtrim($directory, '/');
        $subdirectories = [];
        $files = [];
        if (is_dir($directory) && is_readable($directory)) {
            $dir = dir($directory);
            while (($file = $dir->read()) !== false) {
                if (('.' === $file) || ('..' === $file)) {
                    continue;
                }
                if (is_dir($directory . '/' . $file)) {
                    $subdirectories[] = $directory . '/' . $file;
                } else {
                    $files[] = $directory . '/' . $file;
                }
            }
            $dir->close();
            foreach ($subdirectories as $subdirectory) {
                $files = array_merge($files, $this->getAllPathsFiles($subdirectory));
            }
        }
        return $files;
    }
}
