<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Aggregation\Status;

class StatusList
{
    public function __construct(
       private readonly RemainsProvider  $remainsProvider,
       private readonly TurnoverProvider $turnoverProvider,
    ) {
    }
    public string $remains {
        set(string $value) {
            $this->remains = $this->remainsProvider->provideValue($value, $this->remains);
        }
    }

    public array $turnovers = [] {
        set(string|array $value) {
            $this->turnovers = $this->turnoverProvider->provideValue($value, $this->turnovers);
        }
    }
}
