<?php
namespace Tsum\CashFlow\Block\Adminhtml\Button;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Tsum\CashFlow\Api\StorageRepositoryInterface;
use Tsum\CashFlow\Model\CfItem;
use Tsum\CashFlow\Model\CfItemFactory;
use Tsum\CashFlow\Model\Storage;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var StorageRepositoryInterface
     */
    protected $storageRepository;

    /**
     * @var CfItemFactory
     */
    protected $cfItemFactory;

    /**
     * GenericButton constructor.
     * @param UrlInterface $urlBuilder
     * @param RequestInterface $request
     * @param StorageRepositoryInterface $storageRepository
     * @param CfItemFactory $cfItemFactory
     */
    public function __construct(
        UrlInterface $urlBuilder,
        RequestInterface $request,
        StorageRepositoryInterface $storageRepository,
        CfItemFactory $cfItemFactory
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->storageRepository = $storageRepository;
        $this->cfItemFactory = $cfItemFactory;
    }

    /**
     * Url to send delete requests to.
     * @param string $idParam
     * @param string $id
     *
     * @return string
     */
    public function getDeleteUrl($idParam, $id = null)
    {
        if (!$id) {
            $id = $this->getVerifiedEntityId($idParam);
        }

        return $this->urlBuilder->getUrl('*/*/delete', [$idParam => $id]);
    }

    /**
     * @param string $idParam
     * @return int|null
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getVerifiedEntityId(string $idParam)
    {
        try {
            switch ($idParam) {
                case Storage::ENTITY_ID:
                    return $this->storageRepository->getById(
                        $this->request->getParam($idParam))->getId();

                case CfItem::ENTITY_ID:
                    $cfItemModel = $this->cfItemFactory->create();
                    $cfItemModel->load($idParam);
                    if (!$cfItemModel->getId()) {
                        throw new NoSuchEntityException(__('The CashFlow item with the "%1" ID doesn\'t exist.', $idParam));
                    }

                    return $cfItemModel->getId();
            }
        } catch (NoSuchEntityException $e) {

            return null;
        }
    }
}
