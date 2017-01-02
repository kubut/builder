<?php
namespace BuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="checklist")
 * @ORM\Entity(repositoryClass="BuilderBundle\Repository\ChecklistRepository")
 */
class Checklist
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
     * @ORM\OneToMany(targetEntity="ChecklistItem", mappedBy="checklist", cascade={"persist", "remove"})
     */
    protected $checklistItems;

    /**
     * @var integer
     *
     * @ORM\Column(name="project_id", type="integer")
     */
    protected $projectId;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="checklist", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @ORM\OneToMany(targetEntity="Instance", mappedBy="checklist", cascade={"persist"})
     */
    protected $instances;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->checklistItems = new ArrayCollection();
        $this->instances = new ArrayCollection();
    }

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
     * @return Checklist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->checklistItems;
    }

    /**
     * @param ChecklistItem $item
     *
     * @return Checklist
     */
    public function addChecklistItem(ChecklistItem $item)
    {
        $this->checklistItems[] = $item;

        return $this;
    }

    /**
     * @param ChecklistItem
     */
    public function removeChecklistItem(ChecklistItem $item)
    {
        $this->checklistItems->removeElement($item);
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     *
     * @return Checklist
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * @param Instance $instance
     * @return $this
     */
    public function addInstance(Instance $instance)
    {
        $this->instances[] = $instance;

        return $this;
    }
}