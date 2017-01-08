<?php
namespace BuilderBundle\WebSocket\Channels\Instances\Actions\Server;

use BuilderBundle\WebSocket\Channels\Instances\Actions\BaseInstanceAction;
use BuilderBundle\WebSocket\Settings\ActionHandlerInterface;

/**
 * Class UpdateChecklistItem
 * @package BuilderBundle\WebSocket\Channels\Instances\Actions\Server
 */
class UpdateChecklistItem extends BaseInstanceAction implements ActionHandlerInterface
{
    const ACTION = 'checklist_item_update';

    private $createActionParams = [
        'projectId',
        'checklistId',
        'itemId',
        'itemSolved',
    ];

    /**
     * @param string $action
     *
     * @return bool
     */
    public function check($action)
    {
        return $action === self::ACTION;
    }

    /**
     * @return array
     */
    public function getActionParams()
    {
        return $this->createActionParams;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function run(array $params)
    {
        return $this->instanceService->updateChecklistItem($params);
    }

    /**
     * @return boolean
     */
    public function hasAsyncJob()
    {
        return false;
    }
}