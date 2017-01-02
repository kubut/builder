<?php
namespace BuilderBundle\Factory;

use BuilderBundle\Entity\Instance;

/**
 * Class InstanceFactory
 */
class InstanceFactory implements EntityFactoryInterface
{
    /**
     * @param array $parameters
     * @return mixed
     */
    public function createFromArray(array $parameters = [])
    {
        $template = [
            'name' => '',
            'status' => Instance::CLONING,
            'branch' => '',
            'url' => '',
            'user' => '',
            'buildDate' => new \DateTime(),
            'database' => null,
            'checklist' => null,
            'jiraTaskSymbol' => '',
            'project' => null
        ];
        $parameters = array_merge($template, $parameters);

        $instance = new Instance();
        $instance->setName($parameters['name'])
            ->setStatus($parameters['status'])
            ->setBranch($parameters['branch'])
            ->setUrl($parameters['url'])
            ->setUser($parameters['user'])
            ->setBuildDate($parameters['buildDate'])
            ->setDatabase($parameters['database'])
            ->setChecklist($parameters['checklist'])
            ->setJiraTaskSymbol($parameters['jiraTaskSymbol'])
            ->setProject($parameters['project']);

        return $instance;
    }
}