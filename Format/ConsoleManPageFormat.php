<?php declare(strict_types=1);

namespace Salient\Console\Format;

use Salient\Console\Format\ConsoleFormatter as Formatter;
use Salient\Console\Format\ConsoleTagAttributes as TagAttributes;
use Salient\Console\Format\ConsoleTagFormats as TagFormats;
use Salient\Contract\Console\Format\FormatInterface;

/**
 * Applies Markdown formatting with man page extensions to console output
 */
final class ConsoleManPageFormat implements
    FormatInterface,
    ConsoleFormatterFactory,
    ConsoleTagFormatFactory
{
    private string $Before;
    private string $After;

    public function __construct(string $before = '', string $after = '')
    {
        $this->Before = $before;
        $this->After = $after;
    }

    /**
     * @inheritDoc
     */
    public function apply(?string $string, $attributes = null): string
    {
        if ($string === null || $string === '') {
            return '';
        }

        $before = $this->Before;
        $after = $this->After;

        $tag = $attributes instanceof TagAttributes
            ? $attributes->OpenTag
            : '';

        if ($tag === '##') {
            return '# ' . $string;
        }

        if ($tag === '_') {
            return $string;
        }

        if ($before === '`') {
            return '**`' . $string . '`**';
        }

        if ($before === '```') {
            return $attributes instanceof TagAttributes
                ? $before . $attributes->InfoString . "\n"
                    . $string . "\n"
                    . $attributes->Indent . $after
                : $before . "\n"
                    . $string . "\n"
                    . $after;
        }

        return $before . $string . $after;
    }

    /**
     * @inheritDoc
     */
    public static function getFormatter(): Formatter
    {
        return new Formatter(self::getTagFormats());
    }

    /**
     * @inheritDoc
     */
    public static function getTagFormats(): TagFormats
    {
        return (new TagFormats(false, true))
            ->withFormat(self::TAG_HEADING, new self('***', '***'))
            ->withFormat(self::TAG_BOLD, new self('**', '**'))
            ->withFormat(self::TAG_ITALIC, new self('*', '*'))
            ->withFormat(self::TAG_UNDERLINE, new self('*', '*'))
            ->withFormat(self::TAG_LOW_PRIORITY, new self('', ''))
            ->withFormat(self::TAG_CODE_SPAN, new self('`', '`'))
            ->withFormat(self::TAG_CODE_BLOCK, new self('```', '```'));
    }
}
