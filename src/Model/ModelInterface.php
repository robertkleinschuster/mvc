<?php

declare(strict_types=1);

namespace Mvc\Model;

use Mvc\Bean\TemplateDataBean;
use Mvc\Helper\ValidationHelper;

interface ModelInterface
{
    /**
     * @return TemplateDataBean
     */
    public function getTemplateData(): TemplateDataBean;

    /**
     * @return ValidationHelper
     */
    public function getValidationHelper(): ValidationHelper;

    /**
     * Initialize data source in model
     */
    public function init();

    /**
     * Set limit and current page (starting with 1) from request params
     *
     * @param int $limit
     * @param int $page
     */
    public function setLimit(int $limit, int $page);

    /**
     * Preload data for given ids
     * Load all data with set limit if ids are empty
     *
     * @param array $viewIdMap
     */
    public function find(array $viewIdMap);

    /**
     * Handle form submit
     *
     * @param string $submitModel
     * @param array $viewIdMap
     * @param array $attributes
     */
    public function submit(string $submitModel, array $viewIdMap, array $attributes);
}
