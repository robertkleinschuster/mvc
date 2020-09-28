<?php

declare(strict_types=1);

namespace Mezzio\Mvc\View\Components\Base\Fields;

use Mezzio\Mvc\View\ComponentDataBeanInterface;
use Mezzio\Mvc\View\Components\Base\AbstractField;

abstract class AbstractBadge extends AbstractField
{
    public const TYPE_PILL = 'pill';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $action;


    /**
     * @var string
     */
    private $style;

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'components/base/fields/badge';
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * @param ComponentDataBeanInterface|null $bean
     * @return string
     */
    public function getAction(?ComponentDataBeanInterface $bean = null): string
    {
        if (null === $bean) {
            return $this->action;
        } else {
            return $this->replacePlaceholders($this->action, $bean);
        }
    }

    /**
     * @param string action
     *
     * @return $this
     */
    public function setAction(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasAction(): bool
    {
        return $this->action !== null;
    }

    /**
    * @return string
    */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
    * @param string $style
    *
    * @return $this
    */
    public function setStyle(string $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasStyle(): bool
    {
        return $this->style !== null;
    }

}
