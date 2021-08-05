<?php
declare(strict_types=1);

/**
 * CustomBar | LeSite
 *
 * @package   LeSite_CustomBar
 *
 * @author    Pedro Lima <phgdl.19@gmail.com>
 */

namespace LeSite\CustomBar\Block;

use Magento\Framework\App\Http\Context;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Api\GroupRepositoryInterface;
use LeSite\CustomBar\Model\Config;

class CustomBar extends Template
{
    /**
     * @var string
     */
    protected $_template = 'LeSite_CustomBar::custom-bar.phtml';

    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Context
     */
    protected $httpContext;

    /**
     * @param Config $config
     * @param GroupRepositoryInterface $groupRepository
     * @param Template\Context $context
     * @param Context $httpContext
     * @param array $data
     */
    public function __construct(
        Config $config,
        GroupRepositoryInterface $groupRepository,
        Template\Context $context,
        Context $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->httpContext = $httpContext;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @return string
     */
    public function getCustomerGroup(): string
    {
        return $this->getCustomerGroupName();
    }

    /**
     * @return string
     */
    public function getCustomerGroupName(): string
    {
        try {
            $group = $this->groupRepository->getById($this->getCustomerGroupId());
        } catch (\Exception $exception) {
            $this->config->logger->error($exception->getMessage());
            return '';
        }

        $groupName = $group->getCode();

        if ($this->config->logEnabled) {
            $this->config->logger->info("LeSite_CustomBar - group name: " . $groupName);
        }

        return $groupName;
    }

    /**
     * @return int
     */
    protected function getCustomerGroupId(): int
    {
        return (int)$this->httpContext->getValue('customer_group');
    }

    /**
     * @return array
     */
    public function getCacheKeyInfo(): array
    {
        $keyInfo = parent::getCacheKeyInfo();
        $keyInfo['custom_bar_block_customer_group_id_' . $this->getCustomerGroupId()] = $this->getCustomerGroupId();
        return $keyInfo;
    }
}
