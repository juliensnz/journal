<?php

namespace JournalBundle\Renderer;

use Application\Domain\CardRendererInterface;
use Application\Domain\JournalRendererInterface;
use Symfony\Component\Templating\EngineInterface;

class HtmlJournalRenderer implements JournalRendererInterface
{
    /** @var EngineInterface */
    protected $templating;

    /** @var CardRendererInterface */
    protected $cardRenderer;

    /** @var string */
    protected $template;

    /**
     * @param EngineInterface       $templating
     * @param CardRendererInterface $cardRenderer
     * @param string                $template
     */
    public function __construct(
        EngineInterface $templating,
        CardRendererInterface $cardRenderer,
        $template
    ) {
        $this->templating   = $templating;
        $this->cardRenderer = $cardRenderer;
        $this->template     = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function render(array $cards)
    {
        $cards = array_map(function ($card) {
            return $this->cardRenderer->render($card);
        }, $cards);

        return $this->templating->render($this->template, ['cards' => $cards]);
    }
}
