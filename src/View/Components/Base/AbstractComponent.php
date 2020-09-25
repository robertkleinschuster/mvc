<?php

declare(strict_types=1);

namespace Mezzio\Mvc\View\Components\Base;

use Mezzio\Mvc\View\ComponentModelInterface;
use NiceshopsDev\NiceCore\Option\OptionAwareInterface;
use NiceshopsDev\NiceCore\Option\OptionTrait;

abstract class AbstractComponent implements OptionAwareInterface
{
    use OptionTrait;

    /**
     * @var string
     */
    private $title;

    /**
     * @var AbstractField[]
     */
    private $field_List;

    /**
     * @var ComponentModelInterface
     */
    private $componentModel;

    /**
     * AbstractComponent constructor.
     * @param string $title
     * @param ComponentModelInterface $componentModel
     */
    public function __construct(string $title, ComponentModelInterface $componentModel)
    {
        $this->field_List = [];
        $this->title = $title;
        $this->componentModel = $componentModel;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    /**
     * @return ComponentModelInterface
     */
    public function getComponentModel(): ComponentModelInterface
    {
        return $this->componentModel;
    }

    /**
     * @return AbstractField[]
     */
    public function getFieldList(): array
    {
        return $this->field_List;
    }

    /**
     * @param AbstractField $field
     * @return $this
     */
    protected function addField(AbstractField $field): self
    {
        $this->field_List[] = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return 'components/base/component';
    }
}