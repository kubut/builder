<?php
namespace BuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="database_instances")
 * @ORM\Entity(repositoryClass="BuilderBundle\Repository\DatabaseRepository")
 */
class Database
{
    /**
     * 0 - Baza jest w trakcie zakładania
     * 1 - Baza jest gotowa do użycia
     * 2 - Wystąpił błąd i baza nie została poprawnie założona
     */
    const STATUS_BAKING = 0;
    const STATUS_BAKED = 1;
    const STATUS_BURNED = 2;

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
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    protected $comment;

    /**
     * @ORM\OneToMany(targetEntity="Instance", mappedBy="database", cascade={"persist"})
     */
    protected $instances;


    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status = self::STATUS_BAKING;

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
     * @return Database
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return Database
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Database
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     *
     * @return Database
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
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