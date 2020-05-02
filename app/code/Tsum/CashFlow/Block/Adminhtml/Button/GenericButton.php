<?php
namespace Tsum\CashFlow\Block\Adminhtml\Button;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Tsum\CashFlow\Model\CfItem;
use Tsum\CashFlow\Model\CfItemFactory;
use Tsum\CashFlow\Model\Incomes;
use Tsum\CashFlow\Model\IncomesFactory;

class GenericButton
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var IncomesFactory
     */
    private $incomesFactory;

    /**
     * @var CfItemFactory
     */
    private $cfItemFactory;
    
    public function __construct(
        UrlInterface $urlBuilder,
        RequestInterface $request,
        IncomesFactory $incomesFactory,
        CfItemFactory $cfItemFactory
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->incomesFactory = $incomesFactory;
        $this->cfItemFactory = $cfItemFactory;
    }
    
    public function getDeleteUrl($idParam, $id = null)
    {
        if (!$id) {
            $id = $this->getVerifiedEntityId($idParam);
        }

        return $this->urlBuilder->getUrl('*/*/delete', [$idParam => $id]);
    }
    
    public function getVerifiedEntityId(string $idParam)
    {
        switch ($idParam) {
            case Incomes::ENTITY_ID:
                $incomeModel = $this->incomesFactory->create();
                $incomeModel->load($idParam);

                return $incomeModel->getId();

            case CfItem::ENTITY_ID:
                $cfItemModel = $this->cfItemFactory->create();
                $cfItemModel->load($idParam);

                return $cfItemModel->getId();
        }

            return null;
    }
}
