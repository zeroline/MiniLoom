<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Controlling
 *
 */

namespace zeroline\MiniLoom\Controlling\HTTP;

use RuntimeException;
use zeroline\MiniLoom\Controlling\BaseController as BaseController;
use zeroline\MiniLoom\Data\DataContainer as DataContainer;
use DateTime;
use Exception;
use zeroline\MiniLoom\Helper\XMLConverter;

class Controller extends BaseController
{
    /**
     * @var string
     */
    protected string $responseFormat = PredefinedContentTypeHeaders::JSON;

    /**
     * Return a get variable
     *
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    protected function get(string $key, mixed $fallback = null) : mixed
    {
        if (array_key_exists($key, $_GET)) {
            return $_GET[$key];
        }
        return $fallback;
    }

    /**
     * Returns a post variable
     *
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    protected function post(string $key, mixed $fallback = null) : mixed
    {
        if (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        }
        return $fallback;
    }

    /**
     * Returns the plain request body
     *
     * @return string
     */
    protected function body(): string
    {
        $body = file_get_contents('php://input');
        if ($body === false) {
            throw new RuntimeException("Could not read request body");
        }
        return $body;
    }

    /**
     * Returns the request body as JSON
     * @return mixed
     * @throws RuntimeException
     */
    protected function jsonBody() : mixed
    {
        $result = json_decode($this->body());
        if (is_null($result)) {
            throw new RuntimeException('Invalid JSON body.');
        }
        return $result;
    }

    /**
     * Returns the request body as DataContainer
     * @return DataContainer
     * @throws RuntimeException
     */
    protected function getBodyDataContainer(): DataContainer
    {
        return new DataContainer($this->jsonBody());
    }

    /**
     *
     * @param string $key
     * @return null|string
     */
    protected function getHeader(string $key) : ?string
    {
        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }
        return null;
    }

    /**
     * Return a custom header
     * Auto-replaces - with _, ups all letters and
     * prefixes with HTTP_
     *
     * @param string $key
     * @return null|string
     */
    protected function customHeader(string $key) : ?string
    {
        $key = str_replace('-', '_', strtoupper('HTTP_' . $key));
        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }
        return null;
    }

    /**
     *
     * @param string $location
     * @param int $statusCode
     * @return void
     */
    protected function redirect(string $location, int $statusCode = 303) : void
    {
        header('Location: ' . $location, true, $statusCode);
        die();
    }

    /**
     *
     * @param mixed $data
     * @param bool $success
     * @param string $message
     * @param int $code
     * @return string
     * @throws Exception
     */
    protected function response(mixed $data, bool $success, string $message, int $code): string
    {
        $dateTime = new DateTime();
        $response = array(
            'meta' => array(
                'success' => (bool)$success,
                'error' => (bool)!$success,
                'message' => $message,
                'statusCode' => $code,
                'timestamp' => $dateTime->format('Y-m-d H:i:s.u')
            ),
            'data' => $data
        );

        http_response_code($code);

        switch ($this->responseFormat) {
            case PredefinedContentTypeHeaders::XML:
                PredefinedContentTypeHeaders::setXMLHeader();
                return XMLConverter::toXML($response);
            case PredefinedContentTypeHeaders::JSON:
                PredefinedContentTypeHeaders::setJSONHeader();
                $result = json_encode($response);
                if ($result == false) {
                    throw new RuntimeException("Could not convert response data to JSON");
                }
                return $result;
            default:
                PredefinedContentTypeHeaders::setPlainTextHeader();
                $result = strval($data);
                if ($result == false) {
                    throw new RuntimeException("Could not convert response data to string");
                }
                return $result;
        }
    }

    /**
     *
     * @param mixed $data
     * @param string $message
     * @return string
     * @throws Exception
     */
    protected function responseSuccess(mixed $data, string $message = 'Success'): string
    {
        return $this->response($data, true, $message, 200);
    }

    /**
     *
     * @param mixed $data
     * @param string $message
     * @return string
     * @throws Exception
     */
    protected function responseError(mixed $data, string $message = 'Error'): string
    {
        return $this->response($data, false, $message, 500);
    }

    /**
     *
     * @param mixed $data
     * @param string $message
     * @return string
     * @throws Exception
     */
    protected function responseNotFound(mixed $data, string $message = 'Not found'): string
    {
        return $this->response($data, false, $message, 404);
    }

    /**
     *
     * @param mixed $data
     * @param string $message
     * @return string
     * @throws Exception
     */
    protected function responseAccessDenied(mixed $data, string $message = 'Access denied'): string
    {
        return $this->response($data, false, $message, 403);
    }

    /**
     *
     * @param mixed $data
     * @param string $message
     * @return string
     * @throws Exception
     */
    protected function responseBadRequest(mixed $data, string $message = 'Bad request'): string
    {
        return $this->response($data, false, $message, 400);
    }

    /**
     *
     * @param mixed $data
     * @param string $message
     * @return string
     * @throws Exception
     */
    protected function responseUnauthorized(mixed $data, string $message = 'Unauthorized'): string
    {
        return $this->response($data, false, $message, 401);
    }

    /**
     *
     * @param mixed $data
     * @param string $message
     * @return string
     * @throws Exception
     */
    protected function responseNotImplemented(mixed $data, string $message = 'Not implemented'): string
    {
        return $this->response($data, false, $message, 501);
    }

    /**
     *
     * @param mixed $data
     * @param string $message
     * @return string
     * @throws Exception
     */
    public function responseInvalidData(mixed $data, string $message = 'Invalid data'): string
    {
        return $this->response($data, false, $message, 422);
    }
}
