<?php

namespace JournalBundle\Renderer;

use Application\Domain\CardInterface;
use Application\Domain\CardRendererInterface;
use Symfony\Component\Templating\EngineInterface;

class HtmlCardRenderer implements CardRendererInterface
{
    /** @var EngineInterface */
    protected $templating;

    /**
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function render(CardInterface $card, array $options = [])
    {
        return $this->templating->render($card->getTemplate(), $card->getData($options));
    }
}
