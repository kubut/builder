<?php
namespace BuilderBundle\Factory;

use BuilderBundle\Entity\Checklist;
use BuilderBundle\Entity\ChecklistItem;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ChecklistFactory
 * @package BuilderBundle\Factory
 */
class ChecklistFactory implements EntityFactoryInterface
{
    /**
     * @var ChecklistItemFactory
     */
    protected $checklistItemFactory;

    /**
     * ChecklistFactory constructor.
     * @param ChecklistItemFactory $checklistItemFactory
     */
    public function __construct(ChecklistItemFactory $checklistItemFactory)
    {
        $this->checklistItemFactory = $checklistItemFactory;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function createFromArray(array $parameters = [])
    {
        $template = [
            "name" => '',
            "items" => [],
            "project" => null
        ];
        $parameters = array_merge($template, $parameters);

        $checklist = new Checklist();
        $checklist->setName($parameters['name'])
            ->setProject($parameters['project']);

        $checklist = $this->addItemsFromArray($checklist, $parameters['items']);


        return $checklist;
    }

    /**
     * @param Checklist $checklist
     * @param array     $parameters
     *
     * @return Checklist
     */
    public function addItemsFromArray(Checklist $checklist, array $parameters)
    {
        foreach ($parameters as $item) {
            /** @var ChecklistItem $checkListItem */
            $checkListItem = $this->checklistItemFactory->createFromArray($item);
            $checkListItem->setChecklist($checklist);
            $checklist->addChecklistItem($checkListItem);
        }

        return $checklist;
    }
}