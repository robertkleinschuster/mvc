<?php

declare(strict_types=1);

namespace Mvc\View\Components\Base\Fields;

use Mvc\View\Components\Base\AbstractField;

abstract class AbstractNumber extends AbstractField
{
    /**
     * @var int
     */
    private $decimals;

    /**
     * @var string
     */
    private $thousandsSeparator;

    /**
     * @var string
     */
    private $decimalPoint;

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'components/base/fields/number';
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->decimals ?? 3;
    }

    /**
     * @param int $decimals
     * @return $this
     */
    public function setDecimals(int $decimals): self
    {
        $this->decimals = $decimals;
        return $this;
    }

    /**
     * @return string
     */
    public function getThousandsSeparator(): string
    {
        return $this->thousandsSeparator ?? '.';
    }

    /**
     * @param string $thousandsSeparator
     * @return $this
     */
    public function setThousandsSeparator(string $thousandsSeparator): self
    {
        $this->thousandsSeparator = $thousandsSeparator;
        return $this;
    }

    /**
     * @return string
     */
    public function getDecimalPoint(): string
    {
        return $this->decimalPoint ?? ',';
    }

    /**
     * @param string $decimalPoint
     * @return $this
     */
    public function setDecimalPoint(string $decimalPoint): self
    {
        $this->decimalPoint = $decimalPoint;
        return $this;
    }
}
