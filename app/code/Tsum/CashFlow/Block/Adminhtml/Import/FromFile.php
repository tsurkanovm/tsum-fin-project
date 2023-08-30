<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Block\Adminhtml\Import;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget;

class FromFile extends Widget
{
    protected $_template = 'Tsum_CashFlow::import_from_file.phtml';

    /**
     * @param mixed[] $data
     */
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->setUseContainer(true);
    }
}
