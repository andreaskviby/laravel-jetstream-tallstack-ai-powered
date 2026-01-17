<?php

namespace Setup\Lib;

/**
 * TerminalUI - Beautiful terminal interface for the installer
 *
 * Provides ASCII art, colors, progress bars, spinners, and interactive prompts
 * for a jaw-dropping installation experience.
 */
class TerminalUI
{
    private array $colors = [
        // Base
        'reset'     => "\033[0m",

        // Brand colors (Purple/Cyan gradient)
        'brand'     => "\033[38;5;99m",
        'accent'    => "\033[38;5;39m",
        'highlight' => "\033[38;5;213m",

        // Status colors
        'success'   => "\033[38;5;82m",
        'error'     => "\033[38;5;196m",
        'warning'   => "\033[38;5;214m",
        'info'      => "\033[38;5;75m",

        // Text colors
        'heading'   => "\033[1;97m",
        'text'      => "\033[38;5;252m",
        'muted'     => "\033[38;5;244m",
        'dim'       => "\033[38;5;238m",

        // Special
        'code'      => "\033[48;5;236m\033[38;5;156m",
        'bold'      => "\033[1m",
        'underline' => "\033[4m",

        // Gradient simulation
        'grad1'     => "\033[38;5;135m",
        'grad2'     => "\033[38;5;141m",
        'grad3'     => "\033[38;5;147m",
        'grad4'     => "\033[38;5;153m",
        'grad5'     => "\033[38;5;39m",
    ];

    private array $spinnerFrames = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];
    private int $spinnerIndex = 0;
    private int $terminalWidth;
    private int $terminalHeight;

    public function __construct()
    {
        $this->terminalWidth = $this->getTerminalWidth();
        $this->terminalHeight = $this->getTerminalHeight();
    }

    public function clearScreen(): void
    {
        echo "\033[2J\033[H";
    }

    public function hideCursor(): void
    {
        echo "\033[?25l";
    }

    public function showCursor(): void
    {
        echo "\033[?25h";
    }

    /**
     * Display the main banner with ASCII art
     */
    public function showMainBanner(): void
    {
        $this->clearScreen();

        $banner = <<<'ASCII'

    ╔══════════════════════════════════════════════════════════════════════════════╗
    ║                                                                              ║
    ║   ██╗      █████╗ ██████╗  █████╗ ██╗   ██╗███████╗██╗                       ║
    ║   ██║     ██╔══██╗██╔══██╗██╔══██╗██║   ██║██╔════╝██║                       ║
    ║   ██║     ███████║██████╔╝███████║██║   ██║█████╗  ██║                       ║
    ║   ██║     ██╔══██║██╔══██╗██╔══██║╚██╗ ██╔╝██╔══╝  ██║                       ║
    ║   ███████╗██║  ██║██║  ██║██║  ██║ ╚████╔╝ ███████╗███████╗                  ║
    ║   ╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝  ╚═══╝  ╚══════╝╚══════╝                  ║
    ║                                                                              ║
    ║        ████████╗ █████╗ ██╗     ██╗         ███████╗████████╗ █████╗ ██████╗ ║
    ║        ╚══██╔══╝██╔══██╗██║     ██║         ██╔════╝╚══██╔══╝██╔══██╗██╔══██╗║
    ║           ██║   ███████║██║     ██║         ███████╗   ██║   ███████║██████╔╝║
    ║           ██║   ██╔══██║██║     ██║         ╚════██║   ██║   ██╔══██║██╔══██╗║
    ║           ██║   ██║  ██║███████╗███████╗    ███████║   ██║   ██║  ██║██║  ██║║
    ║           ╚═╝   ╚═╝  ╚═╝╚══════╝╚══════╝    ╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═╝║
    ║                                                                              ║
    ║   ╔════════════════════════════════════════════════════════════════════════╗ ║
    ║   ║                                                                        ║ ║
    ║   ║   🤖  CLAUDE CODE AI-POWERED STARTER KIT  v2.0                        ║ ║
    ║   ║       The Ultimate SaaS Foundation for TALL Stack Developers           ║ ║
    ║   ║                                                                        ║ ║
    ║   ╚════════════════════════════════════════════════════════════════════════╝ ║
    ║                                                                              ║
    ╚══════════════════════════════════════════════════════════════════════════════╝

ASCII;

        echo $this->colorize($banner, 'brand');
        echo "\n";
    }

    /**
     * Display Claude Code requirement banner
     */
    public function showClaudeCodeBanner(): void
    {
        $banner = <<<'ASCII'
    ╔══════════════════════════════════════════════════════════════════════════════╗
    ║                                                                              ║
    ║   🤖 CLAUDE CODE REQUIREMENT                                                 ║
    ║   ═══════════════════════════════════════════════════════════════════════   ║
    ║                                                                              ║
    ║   This starter kit is designed specifically for Claude Code users.           ║
    ║   It integrates deeply with Claude's AI capabilities for:                    ║
    ║                                                                              ║
    ║   ✦ AI-powered landing page generation                                       ║
    ║   ✦ Intelligent todo management with Claude Code agents                      ║
    ║   ✦ Pre-configured development agents and skills                             ║
    ║   ✦ Automated code generation and assistance                                 ║
    ║                                                                              ║
    ║   ┌──────────────────────────────────────────────────────────────────────┐   ║
    ║   │  REQUIREMENTS                                                         │   ║
    ║   │  ✓ Claude Code CLI installed (claude --version)                       │   ║
    ║   │  ✓ Valid Anthropic API key                                            │   ║
    ║   │  ✓ Active Claude subscription (Pro/Team/Enterprise)                   │   ║
    ║   └──────────────────────────────────────────────────────────────────────┘   ║
    ║                                                                              ║
    ╚══════════════════════════════════════════════════════════════════════════════╝
ASCII;

        echo $this->colorize($banner, 'accent');
        echo "\n\n";
    }

    /**
     * Show phase header with progress
     */
    public function showPhaseHeader(int $phase, int $total, string $title, string $icon = '◆'): void
    {
        $percentage = round(($phase / $total) * 100);
        $progressBar = $this->renderProgressBar($phase, $total, 50);

        echo "\n";
        echo $this->colorize("  PHASE {$phase}/{$total}  ", 'heading');
        echo $progressBar;
        echo $this->colorize("  {$percentage}%", 'muted');
        echo "\n\n";

        $this->showSectionBox($title, $icon);
    }

    /**
     * Show a section box with title
     */
    public function showSectionBox(string $title, string $icon = '◆'): void
    {
        $width = 76;
        $titleLen = mb_strlen($title) + 4; // icon + spaces
        $padding = $width - $titleLen - 4;

        echo $this->colorize("  ┌" . str_repeat("─", $width) . "┐\n", 'brand');
        echo $this->colorize("  │  {$icon} " . strtoupper($title) . str_repeat(" ", max(0, $padding)) . "│\n", 'brand');
        echo $this->colorize("  └" . str_repeat("─", $width) . "┘\n", 'brand');
        echo "\n";
    }

    /**
     * Render a progress bar
     */
    public function renderProgressBar(int $current, int $total, int $width = 40): string
    {
        $percentage = $total > 0 ? $current / $total : 0;
        $filled = (int) ($percentage * $width);
        $empty = $width - $filled;

        $bar = $this->colorize(str_repeat('━', $filled), 'accent');
        $bar .= $this->colorize(str_repeat('░', $empty), 'dim');

        return $bar;
    }

    /**
     * Show an info box with content
     */
    public function showInfoBox(string $title, array $lines, string $style = 'info'): void
    {
        $width = 74;

        echo $this->colorize("  ┌" . str_repeat("─", $width) . "┐\n", 'muted');
        echo $this->colorize("  │  ", 'muted');
        echo $this->colorize($title, $style);
        echo $this->colorize(str_repeat(" ", $width - mb_strlen($title) - 4) . "│\n", 'muted');
        echo $this->colorize("  │" . str_repeat(" ", $width) . "│\n", 'muted');

        foreach ($lines as $line) {
            $lineLen = mb_strlen($line);
            $padding = max(0, $width - $lineLen - 4);
            echo $this->colorize("  │  ", 'muted');
            echo $this->colorize($line, 'text');
            echo $this->colorize(str_repeat(" ", $padding) . "│\n", 'muted');
        }

        echo $this->colorize("  └" . str_repeat("─", $width) . "┘\n", 'muted');
        echo "\n";
    }

    /**
     * Show options list for selection
     */
    public function showOptions(array $options, ?string $recommended = null): void
    {
        echo $this->colorize("  ┌" . str_repeat("─", 76) . "┐\n", 'muted');
        echo $this->colorize("  │" . str_repeat(" ", 76) . "│\n", 'muted');

        foreach ($options as $key => $option) {
            $isRecommended = ($key === $recommended);
            $marker = '○';
            $badge = $isRecommended ? $this->colorize('  RECOMMENDED', 'success') : '';

            echo $this->colorize("  │  ", 'muted');
            echo $this->colorize("{$marker} {$key}. ", 'accent');
            echo $this->colorize($option['title'], 'heading');
            echo $badge;

            $lineLen = mb_strlen("{$marker} {$key}. " . $option['title']) + ($isRecommended ? 13 : 0);
            echo $this->colorize(str_repeat(" ", max(0, 72 - $lineLen)) . "│\n", 'muted');

            if (isset($option['description'])) {
                echo $this->colorize("  │     └─ ", 'dim');
                echo $this->colorize($option['description'], 'muted');
                $descLen = mb_strlen($option['description']) + 8;
                echo $this->colorize(str_repeat(" ", max(0, 72 - $descLen)) . "│\n", 'muted');
            }

            if (isset($option['features'])) {
                foreach ($option['features'] as $feature) {
                    echo $this->colorize("  │       • ", 'dim');
                    echo $this->colorize($feature, 'dim');
                    $featLen = mb_strlen($feature) + 10;
                    echo $this->colorize(str_repeat(" ", max(0, 72 - $featLen)) . "│\n", 'muted');
                }
            }

            echo $this->colorize("  │" . str_repeat(" ", 76) . "│\n", 'muted');
        }

        echo $this->colorize("  └" . str_repeat("─", 76) . "┘\n", 'muted');
        echo "\n";
    }

    /**
     * Show checkboxes for multi-select
     */
    public function showCheckboxes(array $items, array $selected = []): void
    {
        echo $this->colorize("  ┌" . str_repeat("─", 76) . "┐\n", 'muted');
        echo $this->colorize("  │" . str_repeat(" ", 76) . "│\n", 'muted');

        foreach ($items as $key => $item) {
            $isSelected = in_array($key, $selected);
            $checkbox = $isSelected ? '[✓]' : '[ ]';
            $checkColor = $isSelected ? 'success' : 'muted';

            echo $this->colorize("  │  ", 'muted');
            echo $this->colorize($checkbox, $checkColor);
            echo $this->colorize(" {$item['title']}", 'text');

            if (isset($item['description'])) {
                echo $this->colorize("  ", 'muted');
                echo $this->colorize($item['description'], 'dim');
            }

            $lineLen = 5 + mb_strlen($item['title']) + (isset($item['description']) ? mb_strlen($item['description']) + 2 : 0);
            echo $this->colorize(str_repeat(" ", max(0, 72 - $lineLen)) . "│\n", 'muted');
        }

        echo $this->colorize("  │" . str_repeat(" ", 76) . "│\n", 'muted');
        echo $this->colorize("  └" . str_repeat("─", 76) . "┘\n", 'muted');
        echo "\n";
    }

    /**
     * Show spinner with message
     */
    public function showSpinner(string $message): void
    {
        $frame = $this->spinnerFrames[$this->spinnerIndex];
        $this->spinnerIndex = ($this->spinnerIndex + 1) % count($this->spinnerFrames);

        echo "\r  " . $this->colorize($frame, 'accent') . " " . $message . str_repeat(" ", 20);
    }

    /**
     * Clear spinner line
     */
    public function clearSpinner(): void
    {
        echo "\r" . str_repeat(" ", 80) . "\r";
    }

    /**
     * Show success message
     */
    public function success(string $message, ?string $duration = null): void
    {
        $durationText = $duration ? $this->colorize("  {$duration}", 'dim') : '';
        echo $this->colorize("  [✓] ", 'success') . $message . $durationText . "\n";
    }

    /**
     * Show error message
     */
    public function error(string $message): void
    {
        echo $this->colorize("  [✗] ", 'error') . $message . "\n";
    }

    /**
     * Show warning message
     */
    public function warning(string $message): void
    {
        echo $this->colorize("  [!] ", 'warning') . $message . "\n";
    }

    /**
     * Show info message
     */
    public function info(string $message): void
    {
        echo $this->colorize("  [ℹ] ", 'info') . $message . "\n";
    }

    /**
     * Show pending message
     */
    public function pending(string $message): void
    {
        echo $this->colorize("  [○] ", 'muted') . $this->colorize($message, 'dim') . "\n";
    }

    /**
     * Show active/processing message
     */
    public function active(string $message): void
    {
        echo $this->colorize("  [●] ", 'accent') . $message . "\n";
    }

    /**
     * Prompt for text input
     */
    public function prompt(string $question, ?string $default = null, ?string $hint = null): string
    {
        if ($hint) {
            echo $this->colorize("  ℹ {$hint}\n", 'dim');
        }

        $defaultText = $default !== null ? $this->colorize(" (default: {$default})", 'dim') : '';
        echo "\n  " . $this->colorize($question, 'text') . $defaultText . "\n";

        echo $this->colorize("  ┌" . str_repeat("─", 60) . "┐\n", 'muted');
        echo $this->colorize("  │ > ", 'accent');

        $input = trim(fgets(STDIN));

        echo $this->colorize("  └" . str_repeat("─", 60) . "┘\n", 'muted');

        return $input !== '' ? $input : ($default ?? '');
    }

    /**
     * Prompt for password (hidden input)
     */
    public function promptPassword(string $question): string
    {
        echo "\n  " . $this->colorize($question, 'text') . "\n";
        echo $this->colorize("  ┌" . str_repeat("─", 60) . "┐\n", 'muted');
        echo $this->colorize("  │ > ", 'accent');

        // Try to hide input on Unix systems
        if (PHP_OS_FAMILY !== 'Windows') {
            system('stty -echo');
            $input = trim(fgets(STDIN));
            system('stty echo');
            echo str_repeat("•", min(strlen($input), 20));
        } else {
            $input = trim(fgets(STDIN));
        }

        echo "\n";
        echo $this->colorize("  └" . str_repeat("─", 60) . "┘\n", 'muted');

        return $input;
    }

    /**
     * Prompt for yes/no confirmation
     */
    public function confirm(string $question, bool $default = true): bool
    {
        $options = $default ? '[Y/n]' : '[y/N]';
        echo "\n  " . $question . " " . $this->colorize($options, 'muted') . ": ";

        $input = strtolower(trim(fgets(STDIN)));

        if ($input === '') {
            return $default;
        }

        return in_array($input, ['y', 'yes', 'ja', 'j']);
    }

    /**
     * Prompt for selection from numbered options
     */
    public function select(string $question, array $options, ?string $default = null): string
    {
        echo "\n  " . $this->colorize($question, 'text') . "\n\n";
        $this->showOptions($options, $default);

        echo "  " . $this->colorize("Select [1-" . count($options) . "]: ", 'accent');
        $input = trim(fgets(STDIN));

        $keys = array_keys($options);
        $index = (int) $input - 1;

        if ($input === '' && $default !== null) {
            return $default;
        }

        if ($index >= 0 && $index < count($keys)) {
            return $keys[$index];
        }

        // Invalid input, return first option
        return $keys[0];
    }

    /**
     * Prompt for multi-line text input
     */
    public function promptMultiline(string $question, ?string $hint = null): string
    {
        if ($hint) {
            echo $this->colorize("  ℹ {$hint}\n", 'dim');
        }

        echo "\n  " . $this->colorize($question, 'text') . "\n";
        echo $this->colorize("  (Press Enter twice or Ctrl+D to finish)\n\n", 'dim');

        echo $this->colorize("  ┌" . str_repeat("─", 70) . "┐\n", 'muted');

        $lines = [];
        $emptyCount = 0;

        while (true) {
            echo $this->colorize("  │ ", 'muted');
            $line = fgets(STDIN);

            if ($line === false) {
                break;
            }

            $line = rtrim($line);

            if ($line === '') {
                $emptyCount++;
                if ($emptyCount >= 2) {
                    break;
                }
            } else {
                $emptyCount = 0;
            }

            $lines[] = $line;
        }

        echo $this->colorize("  └" . str_repeat("─", 70) . "┘\n", 'muted');

        return implode("\n", $lines);
    }

    /**
     * Show success screen with summary
     */
    public function showSuccessScreen(string $projectName, array $features, array $nextSteps): void
    {
        $this->clearScreen();

        $successArt = <<<'ASCII'
    ╔══════════════════════════════════════════════════════════════════════════════╗
    ║                                                                              ║
    ║      _____ _   _  _____ _____ ______ _____ _____   _                         ║
    ║     / ____| | | |/ ____/ ____|  ____/ ____/ ____| | |                        ║
    ║    | (___ | | | | |   | |    | |__ | (___| (___   | |                        ║
    ║     \___ \| | | | |   | |    |  __| \___ \\___ \  | |                        ║
    ║     ____) | |_| | |___| |____| |____ ___) |___) | |_|                        ║
    ║    |_____/ \___/ \_____\_____|______|_____/_____/  (_)                       ║
    ║                                                                              ║
    ║                     🎉 Your project is ready!                                ║
    ║                                                                              ║
    ╚══════════════════════════════════════════════════════════════════════════════╝
ASCII;

        echo $this->colorize($successArt, 'success');
        echo "\n\n";

        // Project summary
        $this->showInfoBox("📦 INSTALLATION SUMMARY", [
            "Project Name:     {$projectName}",
            "Location:         " . getcwd() . "/{$projectName}",
            "",
            "─" . str_repeat("─", 68),
            "",
            "FEATURES INSTALLED",
        ], 'heading');

        foreach ($features as $feature) {
            echo $this->colorize("  ✓ ", 'success') . $feature . "\n";
        }

        echo "\n";

        // Next steps
        $this->showInfoBox("🚀 NEXT STEPS", $nextSteps, 'accent');

        echo "\n";
        echo $this->colorize("  ─" . str_repeat("─", 76) . "\n", 'dim');
        echo $this->colorize("  Built with ❤️  for the Laravel TALL Stack community\n", 'muted');
        echo $this->colorize("  Powered by Claude Code AI\n", 'muted');
        echo $this->colorize("  ─" . str_repeat("─", 76) . "\n\n", 'dim');
    }

    /**
     * Show error screen with recovery options
     */
    public function showErrorScreen(string $error, array $causes, array $actions): void
    {
        echo "\n";
        echo $this->colorize("  ╔══════════════════════════════════════════════════════════════════════════╗\n", 'error');
        echo $this->colorize("  ║  ⚠️  ERROR ENCOUNTERED                                                    ║\n", 'error');
        echo $this->colorize("  ╚══════════════════════════════════════════════════════════════════════════╝\n", 'error');
        echo "\n";

        $this->showInfoBox("WHAT HAPPENED", [$error], 'error');

        if (!empty($causes)) {
            $this->showInfoBox("POSSIBLE CAUSES", $causes, 'warning');
        }

        if (!empty($actions)) {
            $this->showInfoBox("SUGGESTED ACTIONS", $actions, 'info');
        }
    }

    /**
     * Apply color to text
     */
    public function colorize(string $text, string $color): string
    {
        return ($this->colors[$color] ?? '') . $text . $this->colors['reset'];
    }

    /**
     * Get terminal width
     */
    private function getTerminalWidth(): int
    {
        $width = (int) @shell_exec('tput cols 2>/dev/null');
        return $width > 0 ? $width : 80;
    }

    /**
     * Get terminal height
     */
    private function getTerminalHeight(): int
    {
        $height = (int) @shell_exec('tput lines 2>/dev/null');
        return $height > 0 ? $height : 24;
    }

    /**
     * Check if terminal supports colors
     */
    public function supportsColors(): bool
    {
        if (getenv('NO_COLOR') !== false) {
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            return getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON';
        }

        return function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }
}
