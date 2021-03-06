<?php

declare(strict_types=1);

namespace Mvc\View\Components\Navigation;

use Mvc\View\Components\Base\AbstractComponent;

class Navigation extends AbstractComponent
{

    public const TYPE_TABS = 'tabs';
    public const TYPE_PILLS = 'pills';

    /**
     * @var array
     */
    private $component_List = [];

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $active;


    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return 'components/navigation/navigation';
    }

    /**
     * @param AbstractComponent $component
     */
    public function addComponent(AbstractComponent $component)
    {
        if ($this->hasPermissionList()) {
            $component->setPermissionList($this->getPermissionList());
            if ($component->hasPermission() && !in_array($component->getPermission(), $this->getPermissionList())) {
                return;
            }
        }
        $this->component_List[] = $component;
    }

    /**
     * @return AbstractComponent[]
     */
    public function getComponentList(): array
    {
        if ($this->hasPermissionList()) {
            $componentList = $this->component_List;
            return array_values(array_filter($componentList, function ($component) {
                return !$component->hasPermission() || in_array($component->getPermission(), $this->getPermissionList());
            }));
        }
        return $this->component_List;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type ?? self::TYPE_TABS;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->active ?? 0;
    }

    /**
     * @param int $active
     *
     * @return $this
     */
    public function setActive(int $active): self
    {
        $this->active = $active;
        return $this;
    }
}
