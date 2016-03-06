<?php

namespace JournalBundle\Twig;

use JournalBundle\Card\CardRegistry;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Twig extension to render cards
 */
class CardExtension extends \Twig_Extension
{
    /** @var CardRegistry */
    protected $registry;

    /** @var boolean */
    protected $debug;

    /**
     * @param CardRegistry $registry
     * @param boolean      $debug
     */
    public function __construct(CardRegistry $registry, $debug = false)
    {
        $this->registry   = $registry;
        $this->debug      = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'cards',
                [$this, 'renderCards'],
                ['needs_context' => true, 'needs_environment' => true, 'is_safe' => ['html']]
            )
        ];
    }

    /**
     * Return a list of aliases of displayable view elements of the requested type
     * @param array  $context
     * @param string $type
     *
     * @return string
     */
    public function renderCards(\Twig_Environment $twig, array $context, $type, $single = false)
    {
        $cards = $this->getCards($type);
        //If we only want a single card we pick one of them randomly

        if (0 === count($cards)) {
            return '';
        }

        $cards = $single ? [$cards[array_rand($cards)]] : $cards;

        $content  = '';

        $cardCount = count($cards);
        for ($i = 0; $i < $cardCount; $i++) {
            $card = $cards[$i];
            $cardContext = [
                'card' => [
                    'loop'  => [
                        'index'  => $i + 1,
                        'first'  => 0 === $i,
                        'last'   => $cardCount === $i + 1,
                        'length' => $cardCount
                    ]
                ]
            ] + $context;

            if (true === $this->debug) {
                $content .= sprintf("<!-- Start card template: %s -->\n", $card->getTemplate());
            }

            $content .= $twig->render(
                $card->getTemplate($context),
                array_replace_recursive($cardContext, $card->getParameters($context))
            );

            if (true === $this->debug) {
                $content .= sprintf("<!-- End card template: %s -->\n", $card->getTemplate());
            }
        }

        return $content;
    }

    /**
     * Returns card from the registry that are visible in the given context
     *
     * @param string $type
     * @param array  $context
     *
     * @return ViewElementInterface[]
     */
    protected function getCards($type, array $context = [])
    {
        $elements = $this->registry->get($type);
        $result = [];

        foreach ($elements as $element) {
            if (!$element->isVisible($context)) {
                continue;
            }

            $result[] = $element;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'card_extension';
    }
}
