<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Aggregation\Status;

class StatusList
{
    public function __construct(
       private readonly ValueProvider $provider,
    ) {
    }

    public string $remains = '' {
        set {
            $this->remains = $this->provider->provideRemainValue($value, $this->remains);
        }
    }

    public array $turnovers = [] {
        set(string|array $value) {
            $this->turnovers = $this->provider->provideTurnoverValue($value, $this->turnovers);
        }
    }
}
