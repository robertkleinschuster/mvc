<?php

declare(strict_types=1);

namespace Mezzio\Mvc\View;

use Mezzio\Template\TemplateRendererInterface;

class ViewRenderer
{
    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    private $templateFolder;

    /**
     * ViewRenderer constructor.
     * @param TemplateRendererInterface $templateRenderer
     * @param string $templateFolder
     */
    public function __construct(TemplateRendererInterface $templateRenderer, string $templateFolder)
    {
        $this->templateFolder = $templateFolder;
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * @return TemplateRendererInterface
     */
    public function getTemplateRenderer(): TemplateRendererInterface
    {
        return $this->templateRenderer;
    }


    /**
     * @param View $view
     * @return string
     * @throws \NiceshopsDev\Bean\BeanException
     */
    public function render(View $view): string
    {
        $view->getViewModel()->getTemplateData()->setData('layout', $view->getLayout());
        $view->getViewModel()->getTemplateData()->setData('title', $view->getTitle());
        $view->getViewModel()->getTemplateData()->setData('author', $view->getAuthor());
        $view->getViewModel()->getTemplateData()->setData('description', $view->getDescription());
        $view->getViewModel()->getTemplateData()->setData('components', $view->getComponentList());
        $view->getViewModel()->getTemplateData()->setData('model', $view->getViewModel());
        $view->getViewModel()->getTemplateData()->setData('templateFolder', $this->templateFolder);
        return $this->getTemplateRenderer()->render(
            $this->templateFolder . '::' . $view->getTemplate(),
            $view->getViewModel()->getTemplateData()->toArray()
        );
    }
}