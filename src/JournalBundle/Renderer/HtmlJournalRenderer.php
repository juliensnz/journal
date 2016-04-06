<?php

namespace JournalBundle\Renderer;

use Application\Domain\CardRegistry;
use Application\Domain\CardRendererInterface;
use Application\Domain\JournalRendererInterface;
use Symfony\Component\Templating\EngineInterface;

class HtmlJournalRenderer implements JournalRendererInterface
{
    /** @var EngineInterface */
    protected $templating;

    /** @var CardRegistry */
    protected $cardRegistry;

    /** @var CardRendererInterface */
    protected $cardRenderer;

    /** @var string */
    protected $template;

    /**
     * @param EngineInterface       $templating
     * @param CardRegistry          $cardRegistry
     * @param CardRendererInterface $cardRenderer
     * @param string                $template
     */
    public function __construct(
        EngineInterface $templating,
        CardRegistry $cardRegistry,
        CardRendererInterface $cardRenderer,
        $template
    ) {
        $this->templating   = $templating;
        $this->cardRegistry = $cardRegistry;
        $this->cardRenderer = $cardRenderer;
        $this->template     = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function render(array $options = [])
    {
        $cards = $this->cardRegistry->getCards();

        foreach ($options['cards'] as $code => $card) {
            $sortedCards[$card['position']] = [
                'code'    => $code,
                'card'    => $cards[$code],
                'options' => $card
            ];
        }

        $cards = array_map(function ($card) {
            return $this->cardRenderer->render(
                $card['card'],
                $card['options']
            );
        }, $sortedCards);

        return $this->templating->render($this->template, ['cards' => $cards]);
    }
}
