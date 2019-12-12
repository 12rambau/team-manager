<?php

namespace btba\ChatBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class BtbaChatExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('btba_chat.update_interval', $config['update_interval']);
        $container->setParameter('btba_chat.message_class', $config['message_class']);
        $container->setParameter('btba_cat.author_class', $config['author_class']);

    }
}
