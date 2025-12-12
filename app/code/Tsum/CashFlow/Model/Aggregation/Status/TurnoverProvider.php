<?php

namespace Tsum\CashFlow\Model\Aggregation\Status;

class TurnoverProvider
{
    public function provideValue($newValue, $oldValue): array
    {
        $converted = $this->calculateEndOfMonth($newValue);

        if (!in_array($converted, $oldValue, true)) {
            $oldValue[] = $converted;
        }

        return $oldValue;
    }



    private function calculateEndOfMonth(string $timestamp): string
    {
        if (!is_numeric($timestamp)) {
            throw new \InvalidArgumentException('Turnovers must be a valid numeric timestamp.');
        }

        return new \DateTimeImmutable('@' . $timestamp)
            ->setTimezone(new \DateTimeZone('UTC'))
            ->modify('last day of this month 23:59:59')
            ->format('U');
    }
}
