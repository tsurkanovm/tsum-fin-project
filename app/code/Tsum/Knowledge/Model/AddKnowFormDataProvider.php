<?php
declare(strict_types=1);

namespace Tsum\Knowledge\Model;

class AddKnowFormDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    public function getData() : array
    {
        return [ 'list' => [
            0 =>
                [
                    'name'      => 'Veronica',
                    'description'  => 'Costello'
                ],
            1 =>
                [
                    'name'      => 'John',
                    'description'  => 'Doe'
                ],
            2 =>
                [
                    'name'      => 'Jane',
                    'description'  => 'Doe'
                ]
            ]
        ];
    }
}
