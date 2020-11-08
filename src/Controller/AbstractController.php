<?php

declare(strict_types=1);

namespace Pars\Mvc\Controller;

use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\Helper\ValidationHelper;
use Pars\Mvc\Helper\ValidationHelperAwareInterface;
use Pars\Mvc\Model\ModelInterface;
use Pars\Mvc\Parameter\PaginationParameter;
use Pars\Mvc\View\View;
use Throwable;

/**
 * Class AbstractController
 * @package Pars\Mvc\Controller
 */
abstract class AbstractController implements ControllerInterface
{

    /**
     * @var ControllerRequest
     */
    private ControllerRequest $controllerRequest;

    /**
     * @var ControllerResponse
     */
    private ControllerResponse $controllerResponse;

    /**
     * @var ModelInterface
     */
    private ModelInterface $model;

    /**
     * @var PathHelper
     */
    private PathHelper $pathHelper;

    /**
     * @var View
     */
    private ?View $view = null;

    /**
     * @var string|null
     */
    private ?string $template = null;

    /**
     * AbstractController constructor.
     * @param ControllerRequest $controllerRequest
     * @param ControllerResponse $controllerResponse
     * @param ModelInterface $model
     * @param PathHelper $pathHelper
     */
    public function __construct(
        ControllerRequest $controllerRequest,
        ControllerResponse $controllerResponse,
        ModelInterface $model,
        PathHelper $pathHelper
    ) {
        $this->model = $model;
        $this->controllerRequest = $controllerRequest;
        $this->controllerResponse = $controllerResponse;
        $this->pathHelper = $pathHelper;
    }

    /**
     * @return mixed|void
     */
    public function initialize()
    {
        $this->initView();
        $this->initModel();
        $this->handleParameter();
    }

    public function finalize()
    {
        $model = $this->getModel();
        if ($model instanceof ValidationHelperAwareInterface && $model->getValidationHelper()->hasError()) {
            $this->handleValidationError($model->getValidationHelper());
        }
    }

    /**
     * @param Throwable $exception
     * @return mixed|void
     */
    public function error(Throwable $exception)
    {
        $this->getControllerResponse()->setBody("<!DOCTYPE html><html><head><meta charset=\"utf-8\"><title>Error</title><meta name=\"author\" content=\"\"><meta name=\"description\" content=\"\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"></head><body><h1>Error</h1><p>{$exception->getMessage()}</p></body></html>");
    }

    /**
     * @return mixed|void
     */
    public function unauthorized()
    {
        $this->getControllerResponse()->setBody("<!DOCTYPE html><html><head><meta charset=\"utf-8\"><title>Unauthorized</title><meta name=\"author\" content=\"\"><meta name=\"description\" content=\"\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"></head><body><h1>Unauthorized</h1><p>Permission to requested ressource was denied!</p></body></html>");
    }


    /**
     * @return mixed
     */
    abstract protected function initView();

    /**
     * @return mixed
     */
    abstract protected function initModel();

    /**
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    protected function handleParameter()
    {
        if ($this->getControllerRequest()->isAjax()) {
            $this->getControllerResponse()->setMode(ControllerResponse::MODE_JSON);
        }

        if ($this->getControllerRequest()->hasNav()) {
            $navParameter = $this->getControllerRequest()->getNav();
            $this->handleNavigationState(
                $navParameter->getId(),
                $navParameter->getIndex()
            );
        }

        if ($this->getControllerRequest()->hasSearch()) {
            $searchParameter = $this->getControllerRequest()->getSearch();
            $this->pathHelper->addParameter($searchParameter);
            $this->getModel()->handleSearch($searchParameter);
        }

        if ($this->getControllerRequest()->hasOrder()) {
            $orderParameter = $this->getControllerRequest()->getOrder();
            $this->pathHelper->addParameter($orderParameter);
            $this->getModel()->handleOrder($orderParameter);
        }

        if ($this->getControllerRequest()->hasPagingation()) {
            $paginationParameter = $this->getControllerRequest()->getPagination();
            $this->pathHelper->addParameter($paginationParameter);
            $this->getModel()->handlePagination($paginationParameter);
        } elseif ($this->getDefaultLimit() > 0) {
            $paginationParameter = new PaginationParameter();
            $paginationParameter->setLimit($this->getDefaultLimit())->setPage(0);
            $this->getModel()->handlePagination($paginationParameter);
        }

        if ($this->getControllerRequest()->hasId()) {
            $this->pathHelper->setId($this->getControllerRequest()->getId());
            $this->getModel()->handleId($this->getControllerRequest()->getId());
        }

        if ($this->getControllerRequest()->hasMove()) {
            $this->getModel()->handleMove($this->getControllerRequest()->getMove());
        }

        if ($this->getControllerRequest()->hasSubmit()) {
            if ($this->handleSubmitSecurity()) {
                $this->getModel()->handleSubmit(
                    $this->getControllerRequest()->getSubmit(),
                    $this->getControllerRequest()->getId(),
                    $this->getControllerRequest()->getAttribute_List()
                );
            }
        }

        if ($this->getControllerRequest()->hasRedirect()) {
            $this->getControllerResponse()->setRedirect($this->getControllerRequest()->getRedirect()->getLink());
        }
    }

    /**
     * @return int
     */
    protected function getDefaultLimit(): int
    {
        return 0;
    }

    /**
     * handle security checks e.g. csrf token before executing submit in model
     *
     * @return bool
     */
    abstract protected function handleSubmitSecurity(): bool;

    /**
     * gandle validation errors from model after submit
     * e.g. set to flash messanger to display them after redirect
     *
     * @param ValidationHelper $validationHelper
     * @return mixed
     */
    abstract protected function handleValidationError(ValidationHelper $validationHelper);

    /**
     * persist naviation states in session
     * @param string $id
     * @param int $index
     * @return mixed
     */
    abstract protected function handleNavigationState(string $id, int $index);

    /**
     * @return ControllerRequest
     */
    public function getControllerRequest(): ControllerRequest
    {
        return $this->controllerRequest;
    }


    /**
     * @return ControllerResponse
     */
    public function getControllerResponse(): ControllerResponse
    {
        return $this->controllerResponse;
    }

    /**
     * @param bool $reset
     * @param bool $clone
     * @return PathHelper
     */
    public function getPathHelper(): PathHelper
    {
        return $this->pathHelper->reset();
    }

    /**
     *
     * /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface
    {
        return $this->model;
    }


    /**
     * @return View
     */
    public function getView(): View
    {
        return $this->view;
    }

    /**
     * @param View $view
     * @return AbstractController
     */
    protected function setView(View $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasView(): bool
    {
        return null !== $this->view;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTemplate(): bool
    {
        return $this->template !== null;
    }


    /**
     * @param string $key
     * @param $value
     * @return AbstractController
     * @throws \Niceshops\Bean\Type\Base\BeanException
     */
    protected function setTemplateVariable(string $key, $value)
    {
        $this->getModel()->getTemplateData()->setData($key, $value);
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return true;
    }
}
