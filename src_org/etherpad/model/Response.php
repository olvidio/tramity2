<?php

namespace etherpad\model;

use Psr\Http\Message\ResponseInterface;

class Response
{
    const CODE_OK = 0;
    const CODE_WRONG_PARAMETERS = 1;
    const CODE_INTERNAL_ERROR = 2;
    const CODE_NO_SUCH_FUNCTION = 3;
    const CODE_NO_OR_WRONG_API_KEY = 4;

    /** @var array */
    private $data;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        if ($response->getStatusCode() === 200) {
            $this->data = (array)\GuzzleHttp\json_decode($response->getBody(), true);
        } else {
            $this->data = [];
        }
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->getPropertyFromData('code');
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getPropertyFromData($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->getPropertyFromData('message');
    }

    /**
     * Get Response Data Array.
     *
     * By default the whole array will be returned. In order to retrieve just a key based response, provide an array Key.
     *
     * ```php
     * $response = (new Client())->createAuthorIfNotExistsFor(1, 'John Doe');
     * $authorId = $response->getData('authorID');
     * ```
     *
     * @param string $key Access a given key from the data array, if no key is provided all data will be returned.
     * @param mixed $defaultValue If the given key is not found in the array, the $defaultValue will be returned.
     * @return array|string|null
     */
    public function getData($key = null, $defaultValue = null)
    {
        $data = $this->getPropertyFromData('data');

        if (null !== $key) {
            return $data[$key] ?? $defaultValue;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->data;
    }
}
