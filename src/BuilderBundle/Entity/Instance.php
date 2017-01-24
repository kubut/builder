<?php
namespace BuilderBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="instances")
 * @ORM\Entity(repositoryClass="BuilderBundle\Repository\InstanceRepository")
 */
class Instance
{
    /**
     * 0 - Trwa git clone
     * 1 - Trwa budowanie środowiska
     * 2 - Środowisko gotowe do użycia
     * 3 - Wystapił błąd
     */
    const CLONING = 0;
    const BUILDING = 1;
    const READY = 2;
    const ERROR = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="branch", type="string", length=100)
     */
    protected $branch;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=200)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=20)
     */
    protected $user;

    /**
     * @var DateTime
     * @ORM\Column(name="build_date", type="datetime")
     */
    protected $buildDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="database_id", type="integer")
     */
    protected $databaseId;

    /**
     * @var integer
     *
     * @ORM\Column(name="checklist_id", type="integer", nullable=true)
     */
    protected $checklistId;
    /**
     * @ORM\ManyToOne(targetEntity="Database", inversedBy="instance", cascade={"persist"})
     * @ORM\JoinColumn(name="database_id", referencedColumnName="id")
     */
    protected $database;

    /**
     * @ORM\ManyToOne(targetEntity="Checklist", inversedBy="instance", cascade={"persist"})
     * @ORM\JoinColumn(name="checklist_id", referencedColumnName="id", nullable=true)
     */
    protected $checklist;

    /**
     * @var string
     *
     * @ORM\Column(name="jira_symbol", type="string", length=50)
     */
    protected $jiraTaskSymbol;

    /**
     * @var integer
     *
     * @ORM\Column(name="project_id", type="integer")
     */
    protected $projectId;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="instance", cascade={"persist"})
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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     *
     * @return $this
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBuildDate()
    {
        return $this->buildDate;
    }

    /**
     * @param DateTime $buildDate
     *
     * @return $this
     */
    public function setBuildDate($buildDate)
    {
        $this->buildDate = $buildDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatabaseId()
    {
        return $this->databaseId;
    }

    /**
     * @return int
     */
    public function getChecklistId()
    {
        return $this->checklistId;
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param mixed $database
     *
     * @return $this
     */
    public function setDatabase(Database $database)
    {
        $this->database = $database;

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
     * @return $this
     */
    public function setChecklist($checklist)
    {
        $this->checklist = $checklist;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJiraTaskSymbol()
    {
        return $this->jiraTaskSymbol;
    }

    /**
     * @param mixed $jiraTaskSymbol
     *
     * @return $this
     */
    public function setJiraTaskSymbol($jiraTaskSymbol)
    {
        $this->jiraTaskSymbol = $jiraTaskSymbol;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->projectId;
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
     * @return $this
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

}