<?php
declare(strict_types=1);


namespace Mvc\Helper;


interface PathHelperAwareInterface
{
    /**
     * @return PathHelper
     */
    public function getPathHelper(): PathHelper;

    /**
     * @param PathHelper $pathHelper
     *
     * @return $this
     */
    public function setPathHelper(PathHelper $pathHelper);

    /**
     * @return bool
     */
    public function hasPathHelper(): bool;
}