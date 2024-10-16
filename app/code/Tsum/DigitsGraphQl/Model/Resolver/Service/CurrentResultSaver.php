<?php

namespace Tsum\DigitsGraphQl\Model\Resolver\Service;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Tsum\Digits\Api\Data\ResultInterface;
use Tsum\Digits\Api\Data\ResultInterfaceFactory;
use Tsum\Digits\Api\ResultRepositoryInterface;

readonly class CurrentResultSaver
{
    public function __construct(
        private ResultRepositoryInterface $resultRepository,
        private ResultInterfaceFactory $resultFactory,
    ) {
    }

    /**
     * @throws GraphQlInputException
     */
    public function execute(int $hits, int $time, ?int $size): array
    {
        $currentResult = $this->resultFactory->create();
        $currentResult->setHits($hits);
        $currentResult->setTime($time);
        $currentResult->setSize($size ?? ResultRepositoryInterface::DEFAULT_SIZE);
        try {
            return $this->provideOutputResult($this->resultRepository->save($currentResult));
        } catch (LocalizedException) {
            throw new GraphQlInputException(__('GraphQL mutation error. Can`t save current result'));
        }
    }

    private function provideOutputResult(ResultInterface $result): array
    {
        return [
            'creation_time' => $result->getCreationTime()
        ];
    }
}
