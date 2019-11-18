<?php

namespace Tsum\Knowledge\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\State;

class FillProductShortDescCommand extends Command
{
    /**
     * Name argument
     */
    const NAME_ARGUMENT = 'product_id';

    /**
     * Product repository
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var State
     */
    protected $state;

    /**
     * FillCategoryNamesCommand constructor.
     * @param ProductRepository $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param State $state
     */
    public function __construct(
        ProductRepository $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        State $state
    ){
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->state = $state;
        parent::__construct();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('tsum:fill_product_short_desc')
            ->setDescription('Fill products short description attribute (copy from description) command')
            ->setDefinition([
                new InputArgument(
                    self::NAME_ARGUMENT,
                    InputArgument::OPTIONAL,
                    'Product Id'
                )
            ]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $id = $input->getArgument(self::NAME_ARGUMENT);
        if (is_null($id)) {
            $result = $this->productRepository->getList($this->searchCriteriaBuilder->create());
            foreach ($result->getItems() as $product){
                if ($description = $product->getCustomAttribute('description')) {
                    $product->setShortDescription($description->getValue());
                    if ($this->productRepository->save($product)) {
                        $output->writeln('<info>Description was set for  ' . $product->getId() . ' product id!</info>');
                    }
                }
            }
        } else {
            $product = $this->productRepository->getById($id);
            if ($description = $product->getCustomAttribute('description')) {
                $product->setShortDescription($description->getValue());
                if ($this->productRepository->save($product)) {
                    $output->writeln('<info>Description was set for  ' . $id . ' product id!</info>');
                }
            }
        }
    }
}
