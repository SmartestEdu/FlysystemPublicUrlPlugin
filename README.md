# Flysystem Public Url Plugin

This is a [Flysystem](https://github.com/thephpleague/flysystem) Plugin that adds the ablity to get the public url for a file on a filesystem.

Currently supported Adapters:
* [`awss3v2`](https://github.com/thephpleague/flysystem-aws-s3-v2)


## Badges

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/627fa83d-d6bb-4b7c-a7d2-adc5748a81ae/big.png)](https://insight.sensiolabs.com/projects/627fa83d-d6bb-4b7c-a7d2-adc5748a81ae)


## Installation

Using composer:

```bash
composer require smartest-edu/flysystem-public-url-plugin
```


## Enabling

### Symfony2 via [OneupFlysystemBundle](https://github.com/1up-lab/OneupFlysystemBundle)

#### app/config/config.yml
```
oneup_flysystem:
    filesystems:
        default:
            adapter: yourAwss3v2Adapter
            plugins:
                - smartestedu.flysystem.public_url_plugin

services:
    smartestedu.flysystem.public_url_plugin:
        class: SmartestEdu\FlysystemPublicUrlPlugin\AwsUrlPlugin
```


## Useage

```
$publicUrl = $filesystem->getPublicUrl($filename)
```