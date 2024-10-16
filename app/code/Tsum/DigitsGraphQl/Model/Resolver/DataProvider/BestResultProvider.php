<?php

namespace Tsum\DigitsGraphQl\Model\Resolver\DataProvider;

use Magento\Framework\Exception\LocalizedException;
use Tsum\Digits\Api\Data\ResultInterface;
use Tsum\Digits\Api\ResultRepositoryInterface;
use Tsum\DigitsGraphQl\Model\SizeEnum;

readonly class BestResultProvider
{
    public function __construct(
        private ResultRepositoryInterface $resultRepository
    ) {
    }

    public function get(?int $size): array
    {
        $result = [];
        try {
            $bestResults = $this->resultRepository->getThreeVeryBest($size);
            foreach ($bestResults as $bestResult) {
                $result[] = $this->formatResponse($bestResult);
            }
        } catch (LocalizedException) {
            return [];
        }

        return $result;
    }

    private function formatResponse(ResultInterface $result): array
    {
        return [
            'size' => SizeEnum::from($result->getSize())->name,
            'hits' => $result->getHits(),
            'time' => $result->getTime(),
        ];
    }
}
