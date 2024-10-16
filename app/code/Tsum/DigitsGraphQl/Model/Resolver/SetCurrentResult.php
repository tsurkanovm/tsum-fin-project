<?php

namespace Tsum\DigitsGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tsum\DigitsGraphQl\Model\Resolver\Service\CurrentResultSaver;
use Tsum\DigitsGraphQl\Model\SizeEnum;

class SetCurrentResult implements ResolverInterface
{
    public function __construct(
        private readonly CurrentResultSaver $resultSaver
    ) {
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['currentResult']['hits'])) {
            throw new GraphQlInputException(__('Hits quantity is required to register a result'));
        }

        if (!isset($args['currentResult']['time'])) {
            throw new GraphQlInputException(__('Time is required to register a result'));
        }

        $hits = $args['currentResult']['hits'];
        $time = $args['currentResult']['time'];
        $size = SizeEnum::fromName($args['currentResult']['size'] ?? null);

        return $this->resultSaver->execute($hits, $time, $size);
    }
}
