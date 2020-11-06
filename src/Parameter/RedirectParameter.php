<?php

namespace Pars\Mvc\Parameter;

use Pars\Mvc\Controller\ControllerRequest;

class RedirectParameter extends AbstractParameter
{
    public const ATTRIBUTE_LINK = 'link';


    public function getParamterKey(): string
    {
        return ControllerRequest::ATTRIBUTE_REDIRECT;
    }


    /**
     * @param string $link
     * @return $this
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     */
    public function setLink(string $link)
    {
        $this->setAttribute(self::ATTRIBUTE_LINK, $link);
        return $this;
    }

    /**
     * @return string
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    public function getLink(): string
    {
        return $this->getAttribute(self::ATTRIBUTE_LINK);
    }
}
