<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Collections;

use AndyDefer\DomainStructures\Abstracts\AbstractTypedCollection;
use AndyDefer\PhpVo\ValueObjects\EmailVO;

/**
 * Collection of EmailVO instances.
 *
 * Provides helpers to append, deduplicate, and serialize email addresses.
 *
 * @extends AbstractTypedCollection<EmailVO>
 */
final class EmailVOCollection extends AbstractTypedCollection
{
    public function __construct()
    {
        parent::__construct(EmailVO::class);
    }

    /**
     * Return the raw string values of every email in the collection.
     *
     * @return array<int, string> The email string values.
     */
    public function toValues(): array
    {
        return $this->map(fn (EmailVO $email): string => $email->getValue())->toArray();
    }
}
