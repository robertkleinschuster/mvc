<?php

namespace Mezzio\Mvc\Factory;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\UriFactory;
use Mezzio\Mvc\Controller\ControllerResponse;
use Mezzio\Mvc\Exception\MvcException;

class ServerResponseFactory
{
    /**
     * @param ControllerResponse $controllerResponse
     * @return HtmlResponse|JsonResponse|RedirectResponse
     * @throws \NiceshopsDev\NiceCore\Exception
     */
    public function __invoke(ControllerResponse $controllerResponse)
    {
        switch ($controllerResponse->getMode()) {
            case ControllerResponse::MODE_HTML:
                return new HtmlResponse(
                    $controllerResponse->getBody(),
                    $controllerResponse->getStatusCode(),
                    $controllerResponse->getHeaders()
                );
            case ControllerResponse::MODE_JSON:
                $data = [
                    'html' => $controllerResponse->getBody(),
                    'attributes' => $controllerResponse->getAttributes(),
                    'inject' => $controllerResponse->getInjector()->toArray()
                ];
                return new JsonResponse($data, $controllerResponse->getStatusCode(), $controllerResponse->getHeaders());
            case ControllerResponse::MODE_REDIRECT:
                return new RedirectResponse(
                    (new UriFactory())->createUri(
                        $controllerResponse->getAttribute(ControllerResponse::ATTRIBUTE_REDIRECT_URI)
                    )
                );
        }
        throw new MvcException('Invalid Mode set in ControlerResponse.');
    }
}
