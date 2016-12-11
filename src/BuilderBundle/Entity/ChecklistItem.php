<?php
namespace BuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="checklist_item")
 * @ORM\Entity(repositoryClass="BuilderBundle\Repository\ChecklistItemRepository")
 */
class ChecklistItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20)
     */
    protected $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="checklist_id", type="integer")
     */
    protected $checklistId;

    /**
     * @ORM\ManyToOne(targetEntity="Checklist", inversedBy="checklistItem", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="checklist_id", referencedColumnName="id")
     */
    protected $checklist;

    /**
     * @var boolean
     *
     * @ORM\Column(name="solved", type="boolean")
     */
    private $solved;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ChecklistItem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChecklist()
    {
        return $this->checklist;
    }

    /**
     * @param mixed $checklist
     *
     * @return ChecklistItem
     */
    public function setChecklist($checklist)
    {
        $this->checklist = $checklist;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSolved()
    {
        return $this->solved;
    }

    /**
     * @param boolean $solved
     *
     * @return ChecklistItem
     */
    public function setIsSolved($solved)
    {
        $this->solved = $solved;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->checklistId;
    }
}