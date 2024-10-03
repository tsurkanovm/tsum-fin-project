<?php

namespace Tsum\DigitsGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tsum\DigitsGraphQl\Model\Resolver\DataProvider\BestResultProvider;

class BestResult implements ResolverInterface
{
    public function __construct(
        readonly private BestResultProvider $bestResultProvider
    ) {
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $size = $args['size'] ?? null;

        return $this->bestResultProvider->get($size);
    }
}
