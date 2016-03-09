<?php

namespace JournalBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to register tagged cards in the card registry
 */
class RegisterCardsPass implements CompilerPassInterface
{
    /** @staticvar int The default card position */
    const DEFAULT_POSITION = 100;

    /** @staticvar string The registry id */
    const REGISTRY_ID = 'app.card.registry';

    /** @staticvar string */
    const CARD_TAG = 'journal.card';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(static::REGISTRY_ID)) {
            return;
        }

        $registryDefinition = $container->getDefinition(static::REGISTRY_ID);

        foreach ($container->findTaggedServiceIds(static::CARD_TAG) as $serviceId => $tags) {
            foreach ($tags as $tag) {
                $this->registerCard($registryDefinition, $serviceId, $tag);
            }
        }
    }

    /**
     * Register a card to the card registry
     *
     * @param Definition $registryDefinition
     * @param string     $serviceId
     * @param array      $tag
     */
    protected function registerCard($registryDefinition, $serviceId, $tag)
    {
        if (!isset($tag['type'])) {
            throw new \LogicException(sprintf('No type provided for the "%s" card', $serviceId));
        }
        $position = isset($tag['position']) ? $tag['position'] : static::DEFAULT_POSITION;
        $registryDefinition->addMethodCall(
            'add',
            [
                new Reference($serviceId),
                $tag['type'],
                $position
            ]
        );
    }
}
