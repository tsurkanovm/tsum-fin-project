<?php

namespace Tsum\DigitsGraphQl\Model;

use Tsum\Digits\Api\ResultRepositoryInterface;

enum SizeEnum : int
{
    case size_4 = 4;
    case size_5 = 5;

    /**
     * @phpcs:disable Magento2.Functions.StaticFunction
     */
    public static function fromName(?string $name): int
    {
        if ($name !== null) {
            foreach (self::cases() as $case) {
                if ($case->name === $name) {
                    return $case->value;
                }
            }
        }

        return ResultRepositoryInterface::DEFAULT_SIZE;
    }
}
