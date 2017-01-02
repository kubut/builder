<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\Checklist;
use BuilderBundle\Entity\Project;
use BuilderBundle\Factory\ChecklistFactory;
use BuilderBundle\Repository\ChecklistRepository;
use BuilderBundle\Util\ParametersEncryptor;

/**
 * Class ChecklistModel
 * @package BuilderBundle\Services
 */
class ChecklistModel
{
    /** @var ChecklistFactory */
    private $checklistFactory;

    /** @var ChecklistRepository */
    private $checklistRepository;

    /** @var ChecklistItemModel */
    private $checklistItemModel;

    /**
     * ChecklistModel constructor.
     * @param ChecklistFactory $checklistFactory
     * @param ChecklistRepository $checklistRepository
     * @param ChecklistItemModel $checklistItemModel
     */
    public function __construct(
        ChecklistFactory $checklistFactory,
        ChecklistRepository $checklistRepository,
        ChecklistItemModel $checklistItemModel
    )
    {
        $this->checklistFactory = $checklistFactory;
        $this->checklistRepository = $checklistRepository;
        $this->checklistItemModel = $checklistItemModel;
    }

    /**
     * @param Project $project
     * @param array $params
     *
     * @return array
     */
    public function createChecklist(Project $project, array $params)
    {
        $params['project'] = $project;
        $checklist = $this->checklistFactory->createFromArray($params);
        $this->checklistRepository->save($checklist);

        return ['id' => $checklist->getId()];
    }

    /**
     * @param integer $id
     * @param array $params
     *
     * @throws \Exception
     */
    public function editChecklist($id, array $params)
    {
        /** @var Checklist $checklist */
        $checklist = $this->checklistRepository->findById($id);
        if (isset($params['name'])) {
            $checklist->setName($params['name']);
        }
        $checklist = $this->checklistItemModel->updateItems($checklist, $params['items']);

        $this->checklistRepository->save($checklist);
    }

    /**
     * @param integer $id
     */
    public function removeChecklist($id)
    {
        $this->checklistRepository->remove($id);
    }

    /**
     * @param integer $projectId
     * @param integer $offset
     * @param integer $limit
     *
     * @return array
     */
    public function getCheckListByProjectId($projectId, $offset, $limit)
    {
        $checklistCount = $this->checklistRepository->countChecklistsForProject($projectId);
        $checklists = $this->checklistRepository->fetchChecklistByProjectId($projectId, $offset, $limit);
        $checklistIds = array_column($checklists, 'id');
        $checklistItemsData = $this->checklistItemModel->getChecklistItemsByChecklistId($checklistIds);
        $checklistItems = [];
        foreach ($checklistItemsData as $item) {
            $checklistItems[$item['checklistId']][] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'solved' => $item['solved']
            ];
        }

        foreach ($checklists as &$checklist) {
            $checklist['items'] = isset($checklistItems[$checklist['id']]) ? $checklistItems[$checklist['id']] : [];
            $checklist['token'] = ParametersEncryptor::encrypt($checklist['id']);
        }


        usort($checklists, function ($a, $b) {
            return $a["id"] < $b["id"];
        });

        return [
            'count' => $checklistCount,
            'checklists' => $checklists
        ];
    }

    /**
     * @param integer $checklistId
     *
     * @return array
     */
    public function getChecklistPreviewById($checklistId)
    {
        $checklist = $this->checklistRepository->fetchDataWithScope($checklistId, ['name'])[0];
        $checklistItemsData = $this->checklistItemModel->getChecklistItemsByChecklistId([$checklist['id']]);

        usort($checklistItemsData, function ($left, $right) {
            return $left['solved'] - $right['solved'];
        });

        $checklistItems = [];
        foreach ($checklistItemsData as $item) {
            $checklistItems[$item['checklistId']][] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'solved' => $item['solved']
            ];
        }

        $checklist['items'] = isset($checklistItems[$checklist['id']]) ? $checklistItems[$checklist['id']] : [];

        return $checklist;
    }

    /**
     * @param integer $projectId
     * @return array
     */
    public function fetchAllCheckLists($projectId)
    {
        return $this->checklistRepository->fetchAllForProjectId($projectId, ['name']);

    }

    /**
     * @param integer $checklistId
     * @return Checklist
     * @throws \Exception
     */
    public function getById($checklistId)
    {
        return $this->checklistRepository->findById($checklistId);
    }
}