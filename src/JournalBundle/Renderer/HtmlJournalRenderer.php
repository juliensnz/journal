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

        foreach ($cards as $code => $card) {
            $cardPosition[$card['position']] = $code;
        }

        $cards = array_map(function ($code, $card) use ($options) {
            return $this->cardRenderer->render($card, isset($options[$code]) ? $options[$code] : []);
        }, $cardPosition, $cards);

        return $this->templating->render($this->template, ['cards' => $cards]);
    }
}
