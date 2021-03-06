<?php

namespace Refear99\EasyOss;

class OssObject extends Oss
{
    /**
     * 从文件路径上传文件
     *
     * @param string $fileName
     * @param string $filePath
     * @param array $options
     *
     * @return \Aliyun\OSS\Models\PutObjectResult
     */
    public function uploadFromFile($fileName, $filePath, $options = [])
    {
        $file = fopen($filePath, 'r');

        $option = array_merge([
            'Bucket'        => $this->getBucket(),
            'Key'           => $fileName,
            'Content'       => $file,
            'ContentLength' => filesize($filePath)
        ], $options);

        $value = $this->ossClient->putObject($option);

        fclose($file);

        return $value;
    }

    /**
     * 从文本上传文件
     *
     * @param string $fileName
     * @param string $content
     * @param array $options
     *
     * @return \Aliyun\OSS\Models\PutObjectResult
     */
    public function uploadFromContent($fileName, $content, $options = [])
    {
        $option = array_merge([
            'Bucket'        => $this->getBucket(),
            'Key'           => $fileName,
            'Content'       => $content,
            'ContentLength' => strlen($content)
        ], $options);

        return $this->ossClient->putObject($option);
    }

    /**
     * 删除OSS中的文件
     *
     * @param string $fileName
     * @param null|string $sourceBucket
     */
    public function deleteObject($fileName, $sourceBucket = null)
    {
        return $this->ossClient->deleteObject([
            'Bucket' => $sourceBucket ? $sourceBucket : $this->getBucket(),
            'Key'    => $fileName
        ]);
    }

    /**
     * 获取OSS的文件网址
     *
     * @param string $fileName
     * @param bool|false $needSign
     * @param null|\Datetime $expire_time
     *
     * @return mixed|string
     */
    public function getUrl($fileName, $needSign = false, $expire_time = null)
    {
        if ($expire_time instanceof \DateTime) {
            $time = $expire_time;
        } else {
            $time = new \DateTime('+1 day');
        }

        $url = $this->ossClient->generatePresignedUrl([
            'Bucket'  => $this->getBucket(),
            'Key'     => $fileName,
            'Expires' => $time
        ]);

        if (!$needSign) {
            return strtok($url, '?');
        } else {
            return $url;
        }
    }

    /**
     * 复制OSS的文件网址到另一个OSS的文件下
     *
     * @param string $fileName
     * @param string $destFileName
     * @param null|string $sourceBucket
     * @param null|string $destBucket
     *
     * @return \Aliyun\OSS\Models\CopyObjectResult
     */
    public function copyObject($fileName, $destFileName, $sourceBucket = null, $destBucket = null)
    {
        return $this->ossClient->copyObject([
            'SourceBucket' => $sourceBucket ? $sourceBucket : $this->getBucket(),
            'SourceKey' => $fileName,
            'DestBucket' => $destBucket ? $destBucket : $this->getBucket(),
            'DestKey' => $destFileName,
        ]);
    }
}