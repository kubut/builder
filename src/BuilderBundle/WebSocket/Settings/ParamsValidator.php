<?php
namespace BuilderBundle\WebSocket\Settings;
use BuilderBundle\Exception\ExceptionCode;

/**
 * Class ParamsValidator
 */
class ParamsValidator
{
    /**
     * @param array $requestData
     * @param array $obligatoryParams
     *
     * @return mixed
     * @throws \Exception
     */
    public function validateParams(array $requestData, array $obligatoryParams = ['userId', 'userToken', 'action'])
    {
        $omittedParams = [];
        foreach ($obligatoryParams as $obligatoryParam) {
            if (!array_key_exists($obligatoryParam, $requestData)) {
                $omittedParams[] = $obligatoryParam;
            }
        }
        if (!empty($omittedParams)) {
            throw new \Exception(sprintf('Omitted params: %s', implode(',', $omittedParams)), ExceptionCode::OMITTED_PARAMS);
        }
    }

    /**
     * @param string $requestString
     *
     * @return array
     * @throws \Exception
     */
    public function parseRequest($requestString)
    {
        $requestData = json_decode($requestString, true);
        if (is_null($requestData)) {
            throw new \Exception('Invalid json string', ExceptionCode::INVALID_JSON_STRING);
        }

        return $requestData;
    }

    /**
     * @param array $requestData
     * @return null
     */
    public function getRequestId(array $requestData)
    {
        return isset($requestData['requestId']) ? $requestData['requestId'] : null;
    }
}