<?php

namespace Tsum\DigitsGraphQl\Model\Resolver\BestResult;

use Magento\Framework\GraphQl\Query\Resolver\IdentityInterface;
use Tsum\Digits\Model\Result;

class Identity implements IdentityInterface
{
    /**
     * @inheritDoc
     */
    public function getIdentities(array $resolvedData): array
    {
        file_put_contents(BP . '/var/log/identity.log', print_r($resolvedData, true), FILE_APPEND);

        $ids = [];
        foreach ($resolvedData as $result) {
            if (isset($result['id'])) {
                $ids[] = Result::CACHE_TAG . '_' . $result['id'];
            }
        }
        file_put_contents(BP . '/var/log/identity.log', print_r($ids, true), FILE_APPEND);

        return $ids;

    }
}
