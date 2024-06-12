<?php

namespace newplugin\Configuration;

use Exception;
use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Log\Loggable;

class PluginConfiguration
{
    use Loggable;

    const PLUGIN_NAME            = "newplugin";

    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(
        ConfigRepository $configRepository
    ) {
        $this->configRepository  = $configRepository;
    }

    /**
     * @param $configKey
     *
     * @return mixed
     */
    protected function getConfigValue($configKey)
    {
        return $this->configRepository->get(self::PLUGIN_NAME . '.' . $configKey);
    }

    /**
     * @return array|true[]
     */
    public function getSFTPCredentials(): array
    {
        $ftpHost        = $this->getConfigValue('host');
        $ftpUser        = $this->getConfigValue('username');
        $ftpPassword    = $this->getConfigValue('password');
        $ftpPort        = $this->getConfigValue('port');
        $folderPath     = $this->getConfigValue('folderpath');

        if ($ftpHost === null || $ftpUser === null || $ftpPassword === null || $ftpPort === null) {
            $this->getLogger(__METHOD__)->error(self::PLUGIN_NAME . '::error.mandatoryCredentialsAreNotSet',
                [
                    'ftp_hostname'     => $ftpHost,
                    'ftp_username'     => $ftpUser,
                    'ftp_password'     => $ftpPassword,
                    'ftp_port'         => $ftpPort,
                    'ftp_folderPath'   => $folderPath
                ]);

            return [
                'error' => true
            ];
        }

        return [
            'ftp_hostname'     => $ftpHost,
            'ftp_username'     => $ftpUser,
            'ftp_password'     => $ftpPassword,
            'ftp_port'         => $ftpPort,
            'ftp_folderPath'   => $folderPath
        ];
    }
}
