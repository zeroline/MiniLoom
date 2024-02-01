<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Routing
 *
 */

namespace zeroline\MiniLoom\Routing\HTTP;

use zeroline\MiniLoom\Controlling\Caller as Caller;
use zeroline\MiniLoom\Routing\HTTP\RegisteredRoute as RegisteredRoute;
use zeroline\MiniLoom\Routing\HTTP\HTTPVerb as HTTPVerb;
use zeroline\MiniLoom\Routing\HTTP\ParsedRoute as ParsedRoute;
use zeroline\MiniLoom\Helper\XMLConverter as XMLConverter;

use Exception;
use RuntimeException;
use Throwable;

class Router
{
    /**
     *
     * @var array<string, RegisteredRoute>
     */
    protected $routeMappings = array();

    /**
     *
     * @param HTTPVerb $httpVerb
     * @param string $route
     * @return null|RegisteredRoute
     */
    protected function getRegisteredRoute(HTTPVerb $httpVerb, string $route) : ?RegisteredRoute
    {
        $index = $this->calculateIndex($httpVerb, $route);
        if (isset($this->routeMappings[$index])) {
            return $this->routeMappings[$index];
        }
        return null;
    }

    /**
     *
     * @param HTTPVerb $httpVerb
     * @param string $route
     * @return string
     */
    private function calculateIndex(HTTPVerb $httpVerb, string $route) : string
    {
        return hash("crc32b", strtoupper($httpVerb->name).$route);
    }

    /**
     *
     * @param int $statusCode
     * @param string $errorMessage
     * @param string $acceptHeader
     * @return string
     * @throws Exception
     */
    private function prepareErrorResponse(int $statusCode, string $errorMessage, string $acceptHeader) : string
    {
        $response = [
            'error' => [
                'code' => $statusCode,
                'message' => $errorMessage
            ]
        ];

        $contentType = 'application/json'; // Default to JSON

        // Check if XML is requested
        if (strpos($acceptHeader, 'application/xml') !== false) {
            $contentType = 'application/xml';
        } elseif (strpos($acceptHeader, 'application/json') !== false) {
            $contentType = 'application/json';
        } elseif (strpos($acceptHeader, 'text/plain') !== false) {
            $contentType = 'text/plain';
        } elseif (strpos($acceptHeader, 'text/html') !== false) {
            $contentType = 'text/html';
        }

        // Set response headers
        header("Content-Type: $contentType", true, $statusCode);

        switch ($contentType) {
            case 'application/json':
                $responseJson = json_encode($response);
                if ($responseJson === false) {
                    throw new RuntimeException("Failed to encode response to JSON");
                } else {
                    return $responseJson;
                }
            case 'application/xml':
                return XMLConverter::toXML($response);
            case 'text/plain':
                return $response['error']['message'];
            case 'text/html':
                return '<h1>'.$response['error']['message'].'</h1>';
        }
    }

    /**
     *
     * @return ParsedRoute
     */
    protected function parseRequest() : ParsedRoute
    {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        if ($path == null) {
            throw new RuntimeException("Failed to parse request URI");
        }
        $method = $_SERVER['REQUEST_METHOD'];
        $httpVerb = HTTPVerb::fromName(strtoupper($method));
        return new ParsedRoute($httpVerb, $path);
    }

    /**
     *
     * @param HTTPVerb $httpVerb
     * @param string $route
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function on(HTTPVerb $httpVerb, string $route, string $controller, string $action)
    {
        $index = $this->calculateIndex($httpVerb, $route);
        $this->routeMappings[$index] = new RegisteredRoute($httpVerb, $route, $controller, $action);
    }

    /**
     *
     * @param bool $silentError
     * @return void
     * @throws Exception
     */
    public function processRequest(bool $silentError = true) : void
    {
        try {
            $parsedRoute = $this->parseRequest();
            $registeredRoute = $this->getRegisteredRoute($parsedRoute->httpVerb, $parsedRoute->route);
            if ($registeredRoute == null) {
                if ($silentError) {
                    http_response_code(404);
                    return;
                } else {
                    throw new RuntimeException("No route registered for ".$parsedRoute->httpVerb->name." ".$parsedRoute->route, 404);
                }
            }

            $controller = $registeredRoute->controller;
            $action = $registeredRoute->method;

            $body = Caller::call($controller, $action);
            if (is_string($body)) {
                echo $body;
            } else {
                throw new RuntimeException("Controller action must return a string");
            }
        } catch (Throwable $e) {
            $errorCode = $e->getCode();
            if ($errorCode == 0) {
                $errorCode = 500;
            }
            if ($silentError) {
                http_response_code($errorCode);
            } else {
                echo $this->prepareErrorResponse($errorCode, $e->getMessage(), $_SERVER['HTTP_ACCEPT']);
            }
        }
    }
}
