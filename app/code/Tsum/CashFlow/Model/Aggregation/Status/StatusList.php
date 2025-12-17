<?php
declare(strict_types=1);
namespace Tsum\CashFlow\Model\Aggregation\Status;

class StatusList
{
    public function __construct(
       private readonly ValueProvider $provider,
    ) {
    }
//    public string $remains {
//        set(string $value) {
//            $this->remains = $this->remainsProvider->provideValue($value, $this->remains);
//        }
//    }
//
//    public array $turnovers = [] {
//        set(string|array $value) {
//            $this->turnovers = $this->turnoverProvider->provideValue($value, $this->turnovers);
//        }
//    }

    private string $remains = '';
    private array $turnovers = [];

    public function getRemains() : string
    {
        return $this->remains;
    }

    public function getTurnovers() : array
    {
        return $this->turnovers;
    }

    public function setRemains(string $remainsValue): void
    {
        $this->remains = $this->provider->provideRemainValue($remainsValue, $this->remains);
    }

    public function setTurnovers(string $turnoversValue): void
    {
        $this->turnovers = $this->provider->provideTurnoverValue($turnoversValue, $this->turnovers);
    }
}
