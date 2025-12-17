<?php

namespace Tsum\CashFlow\Model\Aggregation\Status;

class ValueProvider
{
    private const string UTC_TIMEZONE = 'UTC';

    public function provideRemainValue(string $newValue, string $oldValue): string
    {
        $converted = $this->calculateEndOfYear($newValue);

        // Initialize if empty, otherwise keep the oldest timestamp
        if (!$oldValue || $converted < $oldValue) {
            return $converted;
        }

        return $oldValue;
    }

    public function provideTurnoverValue(string $newValue, array $oldValue): array
    {
        $converted = $this->calculateEndOfMonth($newValue);

        if (!in_array($converted, $oldValue, true)) {
            $oldValue[] = $converted;
        }

        return $oldValue;
    }


    private function calculateEndOfMonth(string $dateValue): string
    {
        $timestamp = $this->normalizeToTimestamp($dateValue);
        $utc = new \DateTimeZone(self::UTC_TIMEZONE);

        return new \DateTimeImmutable('@' . $timestamp)
            ->setTimezone(new \DateTimeZone('UTC'))
            ->modify('last day of this month 23:59:59')
            ->format('U');
    }

    private function calculateEndOfYear(string $dateValue): string
    {
        $timestamp = $this->normalizeToTimestamp($dateValue);
        $utc = new \DateTimeZone(self::UTC_TIMEZONE);

        return (new \DateTimeImmutable('@' . $timestamp))
            ->setTimezone($utc)
            ->modify('last day of December this year 23:59:59')
            ->format('U');
    }

    private function normalizeToTimestamp(string $dateValue): string
    {
        if (is_numeric($dateValue)) {
            return $dateValue;
        }

        $utc = new \DateTimeZone(self::UTC_TIMEZONE);
        $dt = \DateTimeImmutable::createFromFormat('Y-m-d', $dateValue, $utc);

        if ($dt === false) {
            throw new \InvalidArgumentException('Invalid Remains date format, expected Y-m-d');
        }

        return (string)$dt->getTimestamp();
    }
}
