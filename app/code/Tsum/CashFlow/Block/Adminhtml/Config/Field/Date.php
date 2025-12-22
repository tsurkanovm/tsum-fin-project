<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Block\Adminhtml\Config\Field;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Date extends Field
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        private readonly TimezoneInterface $timezone,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(AbstractElement $element): string
    {
        // Use store/admin locale date format
        $dateFormat = $this->timezone->getDateFormatWithLongYear(); // e.g. "M/d/yyyy"

        $element->setDateFormat($dateFormat);
        $element->setTimeFormat(null);
        $element->setShowsTime(false);
        $element->setImage($this->getViewFileUrl('images/grid-cal.gif'));

        // Make sure calendar JS gets initialized
        $element->setClass(trim((string)$element->getClass() . ' admin__control-text'));

        return $element->getElementHtml();
    }
}
