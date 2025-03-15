<?php declare(strict_types=1);

namespace Salient\Console\Format;

use Salient\Contract\Console\Format\TagAttributesInterface;

/**
 * Tag attributes
 */
final class ConsoleTagAttributes implements TagAttributesInterface
{
    /**
     * Tag identifier
     *
     * @readonly
     * @var ConsoleTagAttributes::*
     */
    public int $Tag;

    /**
     * Tag as it was originally used
     *
     * @readonly
     */
    public string $OpenTag;

    /**
     * Tag depth
     *
     * @readonly
     */
    public int $Depth;

    /**
     * True if the tag has nested tags
     *
     * @readonly
     */
    public ?bool $HasChildren;

    /**
     * Horizontal whitespace before the tag (fenced code blocks only)
     *
     * @readonly
     */
    public ?string $Indent;

    /**
     * Fenced code block info string
     *
     * @readonly
     */
    public ?string $InfoString;

    /**
     * @param ConsoleTagAttributes::* $tag
     */
    public function __construct(
        int $tag,
        string $openTag,
        int $depth = 0,
        ?bool $hasChildren = null,
        ?string $indent = null,
        ?string $infoString = null
    ) {
        $this->Tag = $tag;
        $this->OpenTag = $openTag;
        $this->Depth = $depth;
        $this->HasChildren = $hasChildren;
        $this->Indent = $indent;
        $this->InfoString = $infoString;
    }
}
