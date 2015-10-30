<?php

namespace Refear99\EasyOss;

require_once __DIR__.'/loader.php';

use Aliyun\OSS\OSSClient;
use Aliyun\OSS\Models\OSSOptions;

class Oss {

    /**
     * @var OSSClient
     */
    protected $ossClient;

    /**
     * @var string
     */
    private $bucket;

    /**
     * @param string $serverName
     * @param string $AccessKeyId
     * @param string $AccessKeySecret
     */
    public function __construct($serverName, $AccessKeyId, $AccessKeySecret)
    {
        $this->ossClient = OSSClient::factory([
            OSSOptions::ENDPOINT => $serverName,
            'AccessKeyId' => $AccessKeyId,
            'AccessKeySecret' => $AccessKeySecret
        ]);
    }

    /**
     * @param string $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

}