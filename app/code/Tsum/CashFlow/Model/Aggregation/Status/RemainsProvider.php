<?php

namespace Tsum\CashFlow\Model\Aggregation\Status;

class RemainsProvider
{
    public function provideValue(string $newValue, string $oldValue): string
    {
        $converted = $this->calculateEndOfYear($newValue);

        // Initialize if empty, otherwise keep the oldest timestamp
        if (!isset($oldValue) || $converted < $oldValue) {
            return $converted;
        }

        return $oldValue;
    }

    private function calculateEndOfYear(string $timestamp): string
    {
        if (!is_numeric($timestamp)) {
            throw new \InvalidArgumentException('Remains must be a valid numeric timestamp.');
        }

        return new \DateTimeImmutable('@' . $timestamp)
            ->setTimezone(new \DateTimeZone('UTC'))
            ->modify('last day of December this year 23:59:59')
            ->format('U');
    }
}
