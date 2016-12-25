<?php
namespace BuilderBundle\WebSocket\Channels\Instances;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;

/**
 * Class InstancesActionHandlerFactory
 * @package BuilderBundle\WebSocket\Channels\Instanses
 */
class InstancesActionHandlerFactory
{
    /** @var array of ActionHandlerInterface */
    private $models;

    /**
     * __construct
     */
    public function __construct()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if ($arg instanceof ActionHandlerInterface) {
                $this->models[] = $arg;
            }
        }
    }

    /**
     * @param $action
     * @return ActionHandlerInterface|null
     * @throws \Exception
     */
    public function factory($action)
    {
        $model = null;
        /** @var ActionHandlerInterface $actionModel */
        foreach ($this->models as $actionModel) {
            if ($actionModel->check($action)) {
                return $actionModel;
            }
        }

        if (is_null($model)) {
            throw new \Exception('Undefined action');
        }

        return $model;
    }
}