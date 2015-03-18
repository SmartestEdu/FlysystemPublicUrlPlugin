<?php

namespace SmartestEdu\FlysystemPublicUrlPlugin;

use League\Flysystem\AwsS3v2\AwsS3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
use SmartestEdu\FlysystemPublicUrlPlugin\Exceptions\InvalidAdapterException;
use SmartestEdu\FlysystemPublicUrlPlugin\Exceptions\InvalidFilesystemException;

class AwsUrlPlugin implements PluginInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var AwsS3Adapter
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
        if (!$adapter instanceof AwsS3Adapter) {
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
        return sprintf(
            'https://s3.amazonaws.com/%s/%s',
            $this->adapter->getBucket(),
            $path
        );
    }
}
