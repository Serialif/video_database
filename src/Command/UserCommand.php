<?php

// src/Command/CreateUserCommand.php
namespace App\Command;

use DateTime;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sass:pattern';

    protected const SCSS_FILES = [
        'reset.scss' => 'bases',
        'typography.scss' => 'bases',
        'bases.scss' => 'bases',
        'alerts.scss' => 'components',
        'buttons.scss' => 'components',
        'checkboxes.scss' => 'components',
        'toggle_switches.scss' => 'components',
        'mixins.scss' => 'utils',
        'variables.scss' => 'utils',
    ];

    protected const BASIC_FILES = [
        ['file' => 'reset.scss', 'folder' => 'bases'],
        ['file' => 'typography.scss', 'folder' => 'bases'],
        ['file' => 'bases.scss', 'folder' => 'bases'],
        ['file' => 'alerts.scss', 'folder' => 'components'],
        ['file' => 'buttons.scss', 'folder' => 'components'],
        ['file' => 'checkboxes.scss', 'folder' => 'components'],
        ['file' => 'toggle_switches.scss', 'folder' => 'components'],
        ['file' => 'mixins.scss', 'folder' => 'utils'],
        ['file' => 'variables.scss', 'folder' => 'utils']
    ];

    protected const SCSS_RESET = <<<HTML
* {
    box-sizing: border-box;
    list-style: none;
    margin: 0;
    padding: 0;
}
HTML;

    protected const SCSS_TYPOGRAPHY = <<<HTML
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700&display=swap');

*{
    font-family: 'Lato', sans-serif;
}
HTML;

    protected const SCSS_BASE = <<<HTML
html {
    height: 100%;
    width: 100%;
    font-size: \$font-size-mobile;
    scroll-behavior: smooth;
}

body {
    background: \$background-color-main;
    color: \$color-main;
    display: flex;
    flex-direction: column;
    height: 100%;
}
HTML;

    protected const SCSS_ALERTS = <<<HTML
.alert {
    border-radius: \$border-radius;
    color: #fff;
    padding: 1rem;
    text-align: center;
    width: 100%;
    margin: 1rem 0;
    position: relative;
    pointer-events: none;

    &:hover:after {
        cursor: pointer;
    }

    &:after {
        pointer-events: all;
        content: '\00d7';
        position: absolute;
        top: -.3rem;
        right: .3rem;
        font-size: 2rem;
        font-weight: 300;
    }

    &.success {
        background-color: lighten(\$green, 50);
        //border: 1px solid \$green;
        color:  darken(\$green, 20);

        &:after {
            color:  darken(\$green, 20);
        }
    }

    &.danger {
        background-color: lighten(\$red, 50);
        //border: 1px solid \$red;
        color:  darken(\$red, 20);

        &:after {
            color:  darken(\$red, 20);
        }
    }
}
HTML;

    protected const SCSS_BUTTONS = <<<HTML
body {
    @each \$name, \$color in \$colors {
        .btn-#{\$name} {
            border: \$border;
            border-radius: \$border-radius;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-size: .9rem;
            outline: none;
            padding: .6rem;
            //margin: .3rem;
            height: 2.2rem;
            width: auto;
            background-color: \$color;
            text-decoration: none;
            @if \$name == "white" {
                color: #222;
            }

            &:hover {
                color: #fff;
                text-decoration: none;
                background-color: darken(\$color, 5);
                @if \$name == "white" {
                    color: #222;
                }
            }

            img {
                height: 1rem;
                width: auto;
            }

            &.btn-large{
                width: 100%;
            }

            &.btn-disabled{
                background-color: \$dark-gray;
                cursor: default;
                pointer-events: none;
                user-select: none;
            }
        }
    }
}
HTML;

    protected const SCSS_CHECKBOXES = <<<HTML
@use 'sass:math';
//@import "assets/styles/utils/variables";

\$toggle-height: 1.2rem;
\$toggle-space: .2rem;

.toggle-checkbox {
    display: none;
}

.toggle-label {
    background: \$white;
    border: \$border;
    //border-radius: .25rem;
    cursor: pointer;
    display: inline-block;
    height: \$toggle-height;
    position: relative;
    transition: .3s;
    width: \$toggle-height;

    &::before {
        background: \$green;
        //opacity: 0;
        transform: scale(0);
        //border-radius: .25rem;
        content: "";
        //height: \$toggle-height - (\$toggle-space * 2);
        left: \$toggle-space;
        right: \$toggle-space;
        bottom: \$toggle-space;
        position: absolute;
        top: \$toggle-space;
        transition: .3s;
        //width: \$toggle-height  - (\$toggle-space * 2);
    }
}

.toggle-checkbox:checked + .toggle-label::before {
    //opacity: 1;
    transform: scale(1);
}

.toggle-checkbox:checked + .toggle-label {
}

HTML;

    protected const SCSS_TOGGLE_SWITCHES = <<<HTML
@use 'sass:math';
@import "assets/styles/utils/variables";

\$toggle-height: 1.2rem;
\$toggle-space: .2rem;
\$toggle-translate: \$toggle-height - (2 * \$toggle-space);

.toggle-checkbox {
    display: none;
}

.toggle-label {
    background: \$dark-gray;
    border-radius: math.div(\$toggle-height, 2);
    cursor: pointer;
    display: inline-block;
    height: \$toggle-height;
    position: relative;
    transition: .3s;
    width: \$toggle-height + \$toggle-translate;
    min-width: \$toggle-height + \$toggle-translate;

    &::before {
        background: \$white;
        border-radius: 50%;
        content: "";
        height: \$toggle-height - (\$toggle-space * 2);
        left: \$toggle-space;
        position: absolute;
        top: \$toggle-space;
        transition: .3s;
        width: \$toggle-height  - (\$toggle-space * 2);
    }
}

.toggle-checkbox:checked + .toggle-label::before {
    transform: translateX(\$toggle-translate);
}

.toggle-checkbox:checked + .toggle-label {
    background: \$green;
}

HTML;

    protected const SCSS_MIXINS = <<<HTML
@mixin colorize-button(\$color) {
    border: 1px solid darken(\$color, 40);
    background: darken(\$color, 20);

    &:hover {
        background: \$color;
    }
}

// Striped grid table rows
@mixin striped(\$cols-number, \$row-color1: \$light, \$row-color2: \$white, \$header-color: \$light-gray) {
    > div {
        background: \$row-color1;
    }
    @for \$i from 1 through \$cols-number {
        > div:nth-child(#{(2 * \$cols-number)}n + #{\$i}) {
            background: \$row-color2;
        }
        > div:nth-child( #{\$i}) {
            //background: \$header-color;
            //color: invert(\$header-color);
            background: \$row-color2;
            border-bottom: 1px solid \$black;
            z-index: 10;
            position: sticky;
            top: 0;
        }
    }
}


//. Center a block element without worry top or bottom margin
@mixin push--auto {
    margin: {
        left: auto;
        right: auto;
    }
}

//. ::before and ::after use
@mixin pseudo(\$display: block, \$pos: absolute, \$content: '') {
    content: \$content;
    display: \$display;
    position: \$pos;
}

//. Centering a block
@mixin center-block {
    display: block;
    margin-left: auto;
    margin-right: auto;
}

//. Horizontal & Vertical centering
@mixin center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

//. Vertical centering
@mixin vertical-center {
    position: relative;
    top: 50%;
    -ms-transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
}

@mixin center-vertically {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%),;
}

//. Background gradient
@mixin gradient(\$start-color, \$end-color, \$orientation) {
    background: \$start-color;
    @if \$orientation == 'vertical' {
        background: -webkit-linear-gradient(top, \$start-color, \$end-color);
        background: linear-gradient(to bottom, \$start-color, \$end-color);
    } @else if \$orientation == 'horizontal' {
        background: -webkit-linear-gradient(left, \$start-color, \$end-color);
        background: linear-gradient(to right, \$start-color, \$end-color);
    } @else {
        background: -webkit-radial-gradient(center, ellipse cover, \$start-color, \$end-color);
        background: radial-gradient(ellipse at center, \$start-color, \$end-color);
    }
}

//. Arrow
@mixin arrow(\$color, \$direction, \$size: 6px, \$position: absolute, \$round: false) {
    @include pseudo(\$pos: \$position);
    width: 0;
    height: 0;
    @if \$round {
        border-radius: 3px;
    }
    @if \$direction == down {
        border-left: \$size solid transparent;
        border-right: \$size solid transparent;
        border-top: \$size solid \$color;
        margin-top: 0 - round( \$size / 2.5 );
    } @else if \$direction == up {
        border-left: \$size solid transparent;
        border-right: \$size solid transparent;
        border-bottom: \$size solid \$color;
        margin-bottom: 0 - round( \$size / 2.5 );
    } @else if \$direction == right {
        border-top: \$size solid transparent;
        border-bottom: \$size solid transparent;
        border-left: \$size solid \$color;
        margin-right: -\$size;
    } @else if \$direction == left {
        border-top: \$size solid transparent;
        border-bottom: \$size solid transparent;
        border-right: \$size solid \$color;
        margin-left: -\$size;
    }
}

//. Media query
@mixin screen(\$size) {
    \$mobile: "(max-width: 640px)";
    \$tablet: "(min-width: 768px)";
    \$desktop: "(min-width: 1024px)";
    \$xxl: "(min-width: 1600px)";
    @if \$size == mobile {
        @media only screen and #{\$mobile} {
            @content;
        }
    } @else if \$size == tablet {
        @media only screen and #{\$tablet} {
            @content;
        }
    } @else if \$size == desktop {
        @media only screen and #{\$desktop} {
            @content;
        }
    } @else if \$size == xxl {
        @media only screen and #{\$xxl} {
            @content;
        }
    } @else {
        @media only screen and #{\$size} {
            @content;
        }
    }
}

//. Flex
@mixin flex-column {
    display: flex;
    flex-direction: column;
}

@mixin flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
}

@mixin flex-center-column {
    @include flex-center;
    flex-direction: column;
}

@mixin flex-center-vert {
    display: flex;
    align-items: center;
}

@mixin flex-center-horiz {
    display: flex;
    justify-content: center;
}
HTML;

    protected const SCSS_VARIABLES = <<<HTML
\$purple: #A78BFA;
\$light-purple: lighten(\$purple, 10);
\$dark-purple: darken(\$purple, 10);

\$pink: #F472B6;
\$light-pink: lighten(\$pink, 10);
\$dark-pink: darken(\$pink, 10);

\$red: #9e2727;
\$light-red: lighten(\$red, 10);
\$dark-red: darken(\$red, 10);

\$orange: #f3722c;
\$light-orange: lighten(\$orange, 10);
\$dark-orange: darken(\$orange, 10);

\$yellow: #FBBF24;
\$light-yellow: lighten(\$yellow, 10);
\$dark-yellow: darken(\$yellow, 10);

\$green: #379037;
\$light-green: lighten(\$green, 10);
\$dark-green: darken(\$green, 10);

\$blue: #245590;
\$light-blue: lighten(\$blue, 10);
\$dark-blue: darken(\$blue, 10);

\$gray: #bbb;
\$light-gray: lighten(\$gray, 10);
\$dark-gray: darken(\$gray, 10);


\$light: #f2f2f2;
\$white: #fff;
\$dark: #222;
\$black: #000;

\$colors: (
        "light-purple": \$light-purple,
        "purple": \$purple,
        "dark-purple": \$dark-purple,
        "light-pink": \$light-pink,
        "pink": \$pink,
        "dark-pink": \$dark-pink,
        "light-red": \$light-red,
        "red": \$red,
        "dark-red": \$dark-red,
        "light-orange": \$light-orange,
        "orange": \$orange,
        "dark-orange": \$dark-orange,
        "light-yellow": \$light-yellow,
        "yellow": \$yellow,
        "dark-yellow": \$dark-yellow,
        "light-green": \$light-green,
        "green": \$green,
        "dark-green": \$dark-green,
        "light-blue": \$light-blue,
        "blue": \$blue,
        "dark-blue": \$dark-blue,
        "light-gray": \$light-gray,
        "gray": \$gray,
        "dark-gray": \$dark-gray,
        "dark": \$dark,
        "light": \$light,
        "white": \$white,
);

\$background-color-main: #f7f9fb;
\$color-main: \$black;

\$background-color-header: \$background-color-main;
\$color-header: \$blue;
\$border-color-header: \$light-gray;

\$background-color-footer: \$white;
\$color-footer: \$blue;
\$border-color-footer: \$light-gray;

\$color-link: \$blue;
\$color-link-hover: \$green;

\$border: 1px solid \$light-gray;
\$border-table: 1px solid \$gray;
\$border-focus: 1px solid \$blue;

\$font-size-desktop: 16px;
\$font-size-mobile: 14px;

\$border-radius: .25rem;
\$box-shadow : 0 0 .25rem \$light-gray;
//\$box-shadow : .125rem .125rem .25rem \$light-gray;

\$breakpoints: (
        mobile: 640px,
        tablet: 768px,
        desktop: 1024px,
        xxl: 1600px
);
HTML;

    protected const SCSS_DATA = [
        'reset.scss (bases)' => self::SCSS_RESET,
        'typography.scss (bases)' => self::SCSS_TYPOGRAPHY,
        'bases.scss (bases)' => self::SCSS_BASE,
        'alerts.scss (components)' => self::SCSS_ALERTS,
        'buttons.scss (components)' => self::SCSS_BUTTONS,
        'checkboxes.scss (components)' => self::SCSS_CHECKBOXES,
        'toggle_switches.scss (components)' => self::SCSS_TOGGLE_SWITCHES,
        'mixins.scss (utils)' => self::SCSS_MIXINS,
        'variables.scss (utils)' => self::SCSS_VARIABLES,
    ];


    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates SCSS 7-1 pattern structure.')

            // the full command description shown when running the command with the "--help" option
            ->setHelp(
                "This command allows you to create SCSS 7-1 pattern structure: <fg=magenta>folders</> " .
                "('bases', 'utils', 'layouts', 'components', 'pages', 'themes', 'vendors') and " .
                "<fg=magenta>scss files</> (reset, typography, bases, alerts, buttons, checkboxes, " .
                "toggle_switches, mixins, variables). Also adds their import to app.scss"
            )
            ->addArgument('path', InputArgument::OPTIONAL, 'SCSS 7-1 pattern structure path ');
//            ->addOption(
//                'silent',
//                ['-s'],
//                InputOption::VALUE_OPTIONAL,
//                'Do not ask any interactive question'
//            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            if ($input->getOption('no-interaction')) {
                $path = 'assets/styles/';
                $this->addFolders($path, $io, $output, true);
                $this->writeTitle($output, 'Files');
                $this->addFiles($path, $io, $output, true);
                $this->manageImports($path, $io, $output, true);
            } else {
                $path = $io->ask('Define pattern path:', 'assets/styles/');

                $path = substr($path, strlen($path) - 1) !== '/' ? $path . '/' : $path;

                $this->addFolders($path, $io, $output);

                $this->writeTitle($output, 'Files');
                if ($io->confirm('Do you want to create/overwrite basic files?', true)) {
                    $this->addFiles($path, $io, $output);
                }
                if ($io->confirm('Do you want to manage scss imports?', true)) {
                    $this->manageImports($path, $io, $output);
                }
            }

            $this->writeTitle($output, 'Result');

            $io->success("SCSS 7-1 pattern structure have been updated");
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->writeTitle($output, 'Result');

            $this->writeMessage($output, $e->getMessage(), true, 'error');
            $output->writeln('');
            $io->error("SCSS 7-1 pattern structure haven't been updated");
            return Command::FAILURE;
        }
    }

    /**
     * @param string $path
     * @param SymfonyStyle $io
     * @param OutputInterface $output
     * @param bool|null $force
     */
    protected function addFiles(string $path, SymfonyStyle $io, OutputInterface $output, ?bool $force = false): void
    {
        $fileToAdd = '';

        while ($fileToAdd !== 'stop adding files') {
            $basicFiles = [];
            foreach (self::BASIC_FILES as $basicFile) {
                $file = $basicFile['file'];
                $folder = $basicFile['folder'];
                $tmpFile = "$file ($folder)";
                if (file_exists("$path$folder/_$file")) {
                    $basicFiles[] = '* ' . $tmpFile;
                } else {
                    $basicFiles[] = $tmpFile;
                }
            }

            if (!$force) {
                $basicFiles[] = 'add all files checking if they already exist';
                $basicFiles[] = 'add all files even if they exist';
                $basicFiles[] = 'stop adding files';

                $fileToAdd = $io->choice(
                    'Files to add (files marked with * already exists, they can be overwritten)',
                    $basicFiles,
                    'add all files checking if they already exist'
                );
            } else {
                for ($j = 0; $j < 9; $j++) {
                    $this->createFile($basicFiles[$j], $path, $output, $io, true);
                }
                $output->writeln('');
                break;
            }


            if ($fileToAdd === 'add all files checking if they already exist') {
                for ($j = 0; $j < 9; $j++) {
                    $this->createFile($basicFiles[$j], $path, $output, $io);
                }
                break;
            } elseif ($fileToAdd === 'add all files even if they exist') {
                if ($io->confirm('Are you sure (all existing files will be overwritten)?', true)) {
                    for ($j = 0; $j < 9; $j++) {
                        $this->createFile($basicFiles[$j], $path, $output, $io, true);
                    }
                    $output->writeln('');
                    break;
                }
            } elseif ($fileToAdd === 'stop adding files') {
                $this->writeMessage($output, 'Adding files stopped by user', true, 'warning');
            } else {
                $this->createFile($fileToAdd, $path, $output, $io);
            }

            $output->writeln('');
        }
    }

    /**
     * @param string $fileToAdd
     * @param string $path
     * @param OutputInterface $output
     * @param SymfonyStyle $io
     * @param bool|null $force
     */
    private function createFile(
        string $fileToAdd,
        string $path,
        OutputInterface $output,
        SymfonyStyle $io,
        ?bool $force = false
    ) {
        $fileToAdd = ltrim($fileToAdd, '* ');

        $data = self::SCSS_DATA[$fileToAdd];

        $fileToAdd = rtrim($fileToAdd, ')');

        $position = strpos($fileToAdd, '(');
        $file = substr($fileToAdd, 0, $position - 1);
        $filePath = substr($fileToAdd, $position + 1);

        $fileFullPath = $path . $filePath . '/_' . $file;

        if (file_exists($fileFullPath)) {
            if ($force) {
                file_put_contents($fileFullPath, $data);
            } else {
                $this->writeMessage($output, "$fileFullPath already exists", false, 'warning');
                if ($io->confirm('Overwrite this file?', false)) {
                    file_put_contents($fileFullPath, $data);
                }
            }
            $this->writeMessage($output, "overwritten: $fileFullPath", true, 'warning');
        } else {
            file_put_contents($fileFullPath, $data);
            $this->writeMessage($output, "added: $fileFullPath", true);
        }
    }

    /**
     * @param string $path
     * @param SymfonyStyle $io
     * @param OutputInterface $output
     * @param bool|null $force
     */
    private function addFolders(string $path, SymfonyStyle $io, OutputInterface $output, ?bool $force = false): void
    {
        $folders = ['bases', 'utils', 'layouts', 'components', 'pages', 'themes', 'vendors',];

        $successExists = false;
        $warningExists = false;
        $successFolders = [];
        $warningFolders = [];

        foreach ($folders as $folder) {
            if (file_exists($path . $folder)) {
                $warningExists = true;
                $warningFolders[] = $path . $folder;
            } else {
                mkdir($path . $folder, 0777, true);
                $successExists = true;
                $successFolders[] = $path . $folder;
            }
        }

        $this->writeTitle($output, 'Folders');

        if ($successExists) {
            $this->writeMessage($output, 'These folders have been created:');
            foreach ($successFolders as $successFolder) {
                $this->writeMessage($output, "  * $successFolder", false);
            }
            $output->writeln('');
        }

        if ($warningExists) {
            $this->writeMessage(
                $output,
                'Some folders already exist, they weren\'t be overwritten:',
                'true',
                'warning'
            );
            foreach ($warningFolders as $warningFolder) {
                $this->writeMessage($output, "  * $warningFolder", false, 'warning');
            }
            $output->writeln('');
        }
    }

    /**
     * Adds import to main scss file
     *
     * @param string $path
     * @param SymfonyStyle $io
     * @param OutputInterface $output
     * @param bool|null $force
     */
    private function manageImports(string $path, SymfonyStyle $io, OutputInterface $output, ?bool $force = false)
    {
        if ($force) {
            $appFile = $path . 'app.scss';
        } else {
            $appFile = $io->ask(
                'Define the main scss file where to add the imports:',
                $path . 'app.scss'
            );
        }

        $fileContents = file_get_contents($appFile);
        $imports = '';

        foreach (self::BASIC_FILES as $basicFile) {
            $import = 'import "' . $basicFile['folder'] . '/' .
                str_replace('.scss', '', $basicFile['file']) . '";';
            if (!stripos($fileContents, $import)) {
                $imports .= PHP_EOL . '@' . $import;
            }
        }

        if ($imports !== '') {
            $date = (new DateTime())->format('Y-m-d H:i:s');
            file_put_contents(
                $appFile,
                PHP_EOL . PHP_EOL . '//' . $date . ' Auto added by command "symfony console sass:pattern"' . $imports,
                file_exists($appFile) ? FILE_APPEND : null
            );
            $this->writeMessage($output, "All @import successfully added to $appFile", true, 'success');
        } else {
            $this->writeMessage($output, "All @import were already in $appFile", true, 'warning');
        }
    }

    /**
     * Writes a underscored title to the output and adds a newline before, at the end and after.
     *
     * @param OutputInterface $output
     * @param string $message
     * @param string|null $color
     */
    private function writeTitle(OutputInterface $output, string $message, ?string $color = 'blue'): void
    {
        $output->writeln('');
        $output->writeln("<fg=$color;options=underscore>$message</>");
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
        $types = [
            'success' => ['color' => 'green', 'title' => '[SUCCESS]'],
            'warning' => ['color' => 'yellow', 'title' => '[WARNING]'],
            'error' => ['color' => 'red', 'title' => '[ERROR]'],
        ];

        $title = $showTitle ? $types[$type]['title'] : '';

        $output->writeln("<fg=black;bg={$types[$type]['color']}>$title</> $message");
    }
}
