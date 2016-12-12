<?php
namespace BuilderBundle\Factory;

use BuilderBundle\Entity\Project;
use BuilderBundle\Exception\ExceptionCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserFactory
 * @package BuilderBundle\Entity
 */
class ProjectFactory implements EntityFactoryInterface
{
    private  $template = [
        "name" => '',
        "installScript" => null,
        "sqlFile" => '',
        "sqlUser" => '',
        "configScript" => '',
        "domain" => '',
        "gitPath" => '',
        "gitLogin" => '',
        "gitPass" => '',

    ];
    /**
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function createFromArray(array $parameters = [])
    {
        $this->validateParams($parameters);

        $parameters = array_merge($this->template, $parameters);

        $project = new Project();
        $project->setName($parameters['name'])
            ->setInstallScript($parameters['installScript'])
            ->setSqlFile($parameters['sqlFile'])
            ->setSqlUser($parameters['sqlUser'])
            ->setConfigScript($parameters['configScript'])
            ->setDomain($parameters['domain'])
            ->setGitPath($parameters['gitPath'])
            ->setGitLogin($parameters['gitLogin'])
            ->setGitPass($parameters['gitPass']);


        return $project;
    }

    /**
     * @param array $params
     * @throws \Exception
     */
    public function validateParams(array $params)
    {
        if (strpos($params['configScript'], '$DATABASE_NAME$') === false) {
            throw new \Exception('Config file does not contain $DATABASE_NAME$', ExceptionCode::VALIDATION_PROJECT_CONFIG_FILE);
        }
        if (strlen($params['name']) < 3) {
            throw new \Exception('Name too short', ExceptionCode::VALIDATION_PROJECT_NAME);
        }
        $omittedParams = [];
        foreach ($this->template as $templateItemKey => $templateItemValue) {
            if (is_null($templateItemValue)) {
               continue;
            }

            if (!array_key_exists($templateItemKey, $params)) {
                $omittedParams[] = $templateItemKey;
            }
        }

        if(!empty($omittedParams)) {
            throw new \Exception(sprintf('Omitted params: %s', implode(',', $omittedParams)), ExceptionCode::VALIDATION_PROJECT_PARAMS);
        }
    }
}