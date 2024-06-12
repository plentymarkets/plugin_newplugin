<?php

/**
 * Class FtpClient
 */
class FtpClient
{
    /** @var string */
    private $protocol;
    /** @var string */
    private $host;
    /** @var string */
    private $user;
    /** @var string */
    private $password;
    /** @var string */
    private $port;
    /** @var false|resource */
    private $curlHandle;

    /**
     * FtpClient constructor.
     *
     * @param  string  $protocol
     * @param  string  $host
     * @param  string  $user
     * @param  string  $password
     * @param  string  $port
     */
    public function __construct(string $protocol, string $host, string $user, string $password, string $port)
    {
        $this->protocol = strtolower($protocol) . '://';
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;

        $this->curlHandle = curl_init();
    }

    /**
     *
     */
    public function __destruct()
    {
        if (!empty($this->curlHandle)) {
            @curl_close($this->curlHandle);
        }
    }

    /**
     * @param  string  $remote
     *
     * @return false|int|resource
     */
    private function connect(string $remote)
    {
        curl_reset($this->curlHandle);
        curl_setopt($this->curlHandle, CURLOPT_URL, $this->protocol . $this->host . '/' . $remote);
        curl_setopt($this->curlHandle, CURLOPT_USERPWD, $this->user . ':' . $this->password);
        curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curlHandle, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
        curl_setopt($this->curlHandle, CURLOPT_FTPSSLAUTH, CURLFTPAUTH_TLS);

        return $this->curlHandle;
    }


    /**
     * Get the file names from the specified path
     *
     * @param  string  $path
     *
     * @return array
     */
    public function getFileNames(string $path): array
    {
        $this->curlHandle = $this->connect($path . '/');

        curl_setopt($this->curlHandle, CURLOPT_UPLOAD, 0);
        curl_setopt($this->curlHandle, CURLOPT_FTPLISTONLY, 1);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($this->curlHandle);

        return explode("\n", trim(mb_convert_encoding($result, 'UTF-8', 'UTF-8')));
    }


    /**
     * Get the content of a file
     * @param  string  $fileName
     * @return string
     */
    public function downloadFile(string $fileName): string
    {
        if ($fp = fopen('php://temp', 'r+')) {
            try {
                $this->curlHandle = $this->connect($fileName);
                curl_setopt($this->curlHandle, CURLOPT_UPLOAD, 0);
                curl_setopt($this->curlHandle, CURLOPT_FILE, $fp);
                curl_exec($this->curlHandle);
                rewind($fp);

                return base64_encode(stream_get_contents($fp));
            } finally {
                fclose($fp);
            }
        }

        return '';
    }

    /**
     * @param string $fileName
     * @return string
     * @throws Exception
     */
    public function deleteFile(string $fileName): string
    {
        try {
            $this->curlHandle = $this->connect('');

            curl_setopt($this->curlHandle, CURLOPT_QUOTE, array('DELE ' . $fileName));
            curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($this->curlHandle);

            return $response;
        } catch (Exception $exception) {
            throw $exception;
        }

    }
}
