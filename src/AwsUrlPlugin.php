<?php

namespace SmartestEdu\FlysystemPublicUrlPlugin;

use League\Flysystem\AwsS3v2\AwsS3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
use Oneup\FlysystemBundle\Adapter\LocalWithHost;
use SmartestEdu\FlysystemPublicUrlPlugin\Exceptions\InvalidAdapterException;
use SmartestEdu\FlysystemPublicUrlPlugin\Exceptions\InvalidFilesystemException;

class AwsUrlPlugin implements PluginInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var Filesystem adapter
     */
    protected $adapter;

    /**
     * Set the Filesystem object.
     *
     * Note: We require the Filesystem, not the FilesystemInterface because
     *       the getAdapter method is not a part of the interface. :sadpanda:
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        if (!$filesystem instanceof Filesystem) {
            throw new InvalidFilesystemException(sprintf(
                "AwsUrlPlugin does not support filesystem of type: %s",
                get_class($filesystem)
            ));
        }

        $adapter = $filesystem->getAdapter();
        if (false === $this->isAdapterSupported($adapter)) {
            throw new InvalidAdapterException(sprintf(
                "AwsUrlPlugin does not support adapter of type: %s",
                get_class($adapter)
            ));
        }

        $this->adapter = $adapter;
        $this->filesystem = $filesystem;
    }

    /**
     * Gets the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'getPublicUrl';
    }

    /**
     *
     * @param string $path
     *
     * @return  string The full url to the file
     */
    public function handle($path)
    {
        if ($this->adapter instanceof AwsS3Adapter) {
            return sprintf(
                'https://s3.amazonaws.com/%s/%s',
                $this->adapter->getBucket(),
                $path
            );
        }

        if ($this->adapter instanceof LocalWithHost) {
            return sprintf(
                '%s/%s/%s',
                $this->adapter->getBasePath(),
                $this->adapter->getWebpath(),
                $path
            );
        }
    }

    /**
     * Tells if the adapter is supported.
     *
     * Currently supported adapters:
     * - League\Flysystem\Adapter\AwsS3Adapter
     * - Oneup\FlysystemBundle\Adapter\LocalWithHost
     *
     * @param object Filesystem adapter
     *
     * @return  boolean True if the adapter is supported
     */
    protected function isAdapterSupported($adapter)
    {
        $supportedAdapters = [
            'AwsS3Adapter',
            'LocalWithHost',
        ];

        $isAdapterSupported = false;
        foreach ($supportedAdapters as $supportedAdapter) {
            if (!$adapter instanceof $supportedAdapter) {
                $isAdapterSupported = true;
                break;
            }
        }

        return $isAdapterSupported;
    }
}
