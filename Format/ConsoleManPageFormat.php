<?php declare(strict_types=1);

namespace Salient\Console\Format;

use Salient\Console\Format\ConsoleFormatter as Formatter;
use Salient\Console\Format\ConsoleTagAttributes as TagAttributes;
use Salient\Console\Format\ConsoleTagFormats as TagFormats;
use Salient\Contract\Console\Format\ConsoleFormatInterface;
use Salient\Contract\Console\Format\ConsoleTag as Tag;

/**
 * Applies Markdown formatting with man page extensions to console output
 */
final class ConsoleManPageFormat implements
    ConsoleFormatInterface,
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
    public function apply(?string $text, $attributes = null): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $before = $this->Before;
        $after = $this->After;

        $tag = $attributes instanceof TagAttributes
            ? $attributes->OpenTag
            : '';

        if ($tag === '##') {
            return '# ' . $text;
        }

        if ($tag === '_') {
            return $text;
        }

        if ($before === '`') {
            return '**`' . $text . '`**';
        }

        if ($before === '```') {
            return $attributes instanceof TagAttributes
                ? $before . $attributes->InfoString . "\n"
                    . $text . "\n"
                    . $attributes->Indent . $after
                : $before . "\n"
                    . $text . "\n"
                    . $after;
        }

        return $before . $text . $after;
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
            ->withFormat(Tag::HEADING, new self('***', '***'))
            ->withFormat(Tag::BOLD, new self('**', '**'))
            ->withFormat(Tag::ITALIC, new self('*', '*'))
            ->withFormat(Tag::UNDERLINE, new self('*', '*'))
            ->withFormat(Tag::LOW_PRIORITY, new self('', ''))
            ->withFormat(Tag::CODE_SPAN, new self('`', '`'))
            ->withFormat(Tag::CODE_BLOCK, new self('```', '```'));
    }
}
