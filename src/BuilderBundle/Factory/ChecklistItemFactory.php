<?php
namespace BuilderBundle\Factory;

use BuilderBundle\Entity\Checklist;
use BuilderBundle\Entity\ChecklistItem;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ChecklistItemFactory
 * @package BuilderBundle\Factory
 */
class ChecklistItemFactory implements EntityFactoryInterface
{
    /**
     * @param array $parameters
     * @return mixed
     */
    public function createFromArray(array $parameters = [])
    {
        $template = [
            "name" => '',
            "solved" => false,
        ];
        $parameters = array_merge($template, $parameters);

        $checklist = new ChecklistItem();
        $checklist->setName($parameters['name'])
            ->setIsSolved($parameters['solved']);

        return $checklist;
    }
}