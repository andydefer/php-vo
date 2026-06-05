<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Records;

use AndyDefer\DomainStructures\Abstracts\AbstractRecord;
use AndyDefer\PhpVo\Enums\Currency;
use AndyDefer\PhpVo\ValueObjects\Amount;
use AndyDefer\PhpVo\ValueObjects\AmountVO;

/**
 * MoneyRecord - Data container for monetary values.
 * 
 * @package AndyDefer\PhpVo\Records
 */
final class MoneyRecord extends AbstractRecord
{
    /**
     * @param Amount $amount The monetary amount
     * @param Currency $currency The currency of the amount
     */
    public function __construct(
        public AmountVO $amount,
        public Currency $currency
    ) {}
}
