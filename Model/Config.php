<?php
declare(strict_types=1);

/**
 * CustomBar | LeSite
 *
 * @package   LeSite_CustomBar
 *
 * @author    Pedro Lima <phgdl.19@gmail.com>
 */

namespace LeSite\CustomBar\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

class Config
{
    const CONFIG_PATH = 'custombar/general/';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @var bool
     */
    public $logEnabled;

    /**
     * @var bool
     */
    public $enable;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->enable = $this->isEnabled();
        $this->logEnabled = $this->getEnableLogs();
    }

    /**
     * @param string $config
     * @return mixed
     */
    protected function getConfig(string $config)
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH . $config,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->getConfig('enabled');
    }

    /**
     * @return bool
     */
    public function getEnableLogs(): bool
    {
        return $this->enable && (bool)$this->getConfig('logs_enabled');
    }
}
