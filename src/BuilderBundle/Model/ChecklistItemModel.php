<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\Checklist;
use BuilderBundle\Entity\ChecklistItem;
use BuilderBundle\Exception\ExceptionCode;
use BuilderBundle\Factory\ChecklistFactory;
use BuilderBundle\Factory\ChecklistItemFactory;
use BuilderBundle\Repository\ChecklistItemRepository;

/**
 * Class ChecklistItemModel
 * @package BuilderBundle\Model
 */
class ChecklistItemModel
{
    /** @var ChecklistFactory */
    private $checklistFactory;

    /** @var ChecklistItemRepository  */
    private $checklistItemRepository;

    /**
     * ChecklistItemModel constructor.
     * @param ChecklistFactory $checklistFactory
     * @param ChecklistItemRepository $checklistItemRepository
     */
    public function __construct(
        ChecklistFactory $checklistFactory,
        ChecklistItemRepository $checklistItemRepository
    )
    {
        $this->checklistFactory = $checklistFactory;
        $this->checklistItemRepository = $checklistItemRepository;
    }


    /**
     * @param integer $id
     * @param integer $isSolved
     *
     * @return ChecklistItem
     * @throws \Exception
     */
    public function editChecklistItem($id, $isSolved)
    {
        /** @var ChecklistItem $checklistItem */
        $checklistItem = $this->checklistItemRepository->findById($id);
        $checklistItem->setIsSolved($isSolved);

        return $checklistItem;
    }

    /**
     * @param Checklist $checklist
     * @param array $items
     * @return Checklist
     * @throws \Exception
     */
    public function updateItems(Checklist $checklist, array $items)
    {
        $itemsToCreate = [];
        $itemsToDelete = [];
        foreach ($items as $item) {
            if (!isset($item['id'])) {
                $itemsToCreate[] = $item;
                continue;
            }
            if (isset($item['deleted']) && ($item['deleted'])) {
                $itemsToDelete[] = $item['id'];
                continue;
            }
            /** @var ChecklistItem $checklistItem */
            $checklistItem = $this->checklistItemRepository->findById($item['id']);
            $checklistItem
                ->setName($item['name'])
                ->setIsSolved($item['solved']);
            $checklistItem->setChecklist($checklist);
            $checklist->addChecklistItem($checklistItem);
        }

        $this->checklistItemRepository->removeItemsById($itemsToDelete);

        return $this->checklistFactory->addItemsFromArray($checklist, $itemsToCreate);
    }

    /**
     * @param array $checklistIds
     *
     * @return array
     */
    public function getChecklistItemsByChecklistId($checklistIds)
    {
        return $this->checklistItemRepository->fetchChecklistItemByChecklistIds($checklistIds);
    }

    /**
     * @param ChecklistItem $checklistItem
     */
    public function saveItem(ChecklistItem $checklistItem)
    {
        $this->checklistItemRepository->save($checklistItem);
    }
}