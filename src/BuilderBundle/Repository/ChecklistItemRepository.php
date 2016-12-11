<?php
namespace BuilderBundle\Repository;

use BuilderBundle\Entity\ChecklistItem;
use BuilderBundle\Entity\Project;
use BuilderBundle\Exception\ExceptionCode;
use Doctrine\ORM\EntityRepository;

/**
 * Class ChecklistItemRepository
 * @package BuilderBundle\Repository
 */
class ChecklistItemRepository extends EntityRepository
{
    /**
     * @param ChecklistItem $checklistItem
     */
    public function save(ChecklistItem $checklistItem)
    {
        $this->_em->persist($checklistItem);
        $this->_em->flush();
        $this->_em->refresh($checklistItem);
    }

    /**
     * @param ChecklistItem $checklistItem
     */
    public function persist(ChecklistItem $checklistItem)
    {
        $this->_em->persist($checklistItem);
    }

    /**
     * @param integer $id
     *
     * @return Project
     * @throws \Exception
     */
    public function findById($id)
    {
        $checklistItem = $this->find($id);
        if (is_null($checklistItem)) {
            throw new \Exception('Checklist item does not exist', ExceptionCode::CHECKLIST_ITEM_NOT_EXIST);
        }

        return $checklistItem;
    }

    /**
     * @param array $scope
     * @return array
     */
    public function fetchDataWithScope(array $scope)
    {
        $qb = $this->createQueryBuilder('cli')
            ->select('cli.id');

        foreach ($scope as $scopeItem) {
            $qb->addSelect('cli.' . $scopeItem);
        }

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param array $checklistIds
     * @return array
     */
    public function fetchChecklistItemByChecklistIds(array $checklistIds)
    {
        return $this->createQueryBuilder('cli')
            ->where('cli.checklistId IN (:checklistIds)')
            ->setParameter('checklistIds', $checklistIds)
            ->getQuery()
            ->getArrayResult();
    }
}