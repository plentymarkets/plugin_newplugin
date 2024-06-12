<?php

namespace newplugin\Clients;

use Exception;
use newplugin\Configuration\PluginConfiguration;
use Plenty\Modules\Plugin\Libs\Contracts\LibraryCallContract;
use Plenty\Plugin\Log\Loggable;

class SFTPClient
{
    use Loggable;

    const TRANSFER_PROTOCOL = 'FTP';

    /** @var LibraryCallContract */
    private $library;

    /** @var array */
    private $credentials;

    /**
     * SFTPClient constructor.
     *
     * @param  LibraryCallContract  $library
     * @param  PluginConfiguration  $pluginConfig
     *
     * @throws \Exception
     */
    public function __construct(LibraryCallContract $library, PluginConfiguration $pluginConfig)
    {
        $this->library = $library;

        try {
            $this->credentials = $pluginConfig->getSFTPCredentials();
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function readFileNames()
    {

        $result = $this->library->call(PluginConfiguration::PLUGIN_NAME . '::ftp_readFiles', [
            'transferProtocol' => self::TRANSFER_PROTOCOL,
            'host'             => $this->credentials['ftp_hostname'],
            'user'             => $this->credentials['ftp_username'],
            'password'         => $this->credentials['ftp_password'],
            'port'             => $this->credentials['ftp_port'],
            'folderPath'       => $this->credentials['ftp_folderPath']
        ]);

        if (is_array($result) && array_key_exists('error', $result) && $result['error'] === true) {
            $this->getLogger(__METHOD__)
                ->error(PluginConfiguration::PLUGIN_NAME . '::error.readFilesError',
                    [
                        'errorMsg'   => $result['error_msg'],
                        'errorFile'  => $result['error_file'],
                        'errorLine'  => $result['error_line'],
                        'host'       => $this->credentials['ftp_hostname'],
                        'user'       => $this->credentials['ftp_username'],
                        'port'       => $this->credentials['ftp_port'],
                        'folderPath'       => $this->credentials['ftp_folderPath']
                    ]
                );

            throw new \Exception($result['error_msg']);
        }

        return $result;
    }

    /**
     * @param string $fileName
     * @return array
     * @throws Exception
     */
    public function deleteFile(string $fileName)
    {

        $result = $this->library->call(PluginConfiguration::PLUGIN_NAME . '::ftp_deleteFile', [
            'transferProtocol' => self::TRANSFER_PROTOCOL,
            'host'             => $this->credentials['ftp_hostname'],
            'user'             => $this->credentials['ftp_username'],
            'password'         => $this->credentials['ftp_password'],
            'port'             => $this->credentials['ftp_port'],
            'folderPath'       => $this->credentials['ftp_folderPath'],
            'fileName'         => $fileName
        ]);

        if (is_array($result) && array_key_exists('error', $result) && $result['error'] === true) {
            $this->getLogger(__METHOD__)
                ->error(PluginConfiguration::PLUGIN_NAME . '::error.deleteFileError',
                    [
                        'errorMsg'   => $result['error_msg'],
                        'errorFile'  => $result['error_file'],
                        'errorLine'  => $result['error_line'],
                        'host'       => $this->credentials['ftp_hostname'],
                        'user'       => $this->credentials['ftp_username'],
                        'port'       => $this->credentials['ftp_port'],
                        'folderPath'       => $this->credentials['ftp_folderPath'],
                        'fileName'   => $fileName
                    ]
                );

            throw new \Exception($result['error_msg']);
        }

        return $result;
    }

    public function downloadFile(string $fileName)
    {

        $result = $this->library->call(PluginConfiguration::PLUGIN_NAME . '::ftp_downloadFile', [
            'transferProtocol' => self::TRANSFER_PROTOCOL,
            'host'             => $this->credentials['ftp_hostname'],
            'user'             => $this->credentials['ftp_username'],
            'password'         => $this->credentials['ftp_password'],
            'port'             => $this->credentials['ftp_port'],
            'folderPath'       => $this->credentials['ftp_folderPath'],
            'fileName'         => $fileName
        ]);

        return $result;
    }
}
