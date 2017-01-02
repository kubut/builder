<?php
namespace BuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="projects")
 * @ORM\Entity(repositoryClass="BuilderBundle\Repository\ProjectRepository")
 */
class Project
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
     * @var string
     *
     * @ORM\Column(name="install_script", type="text", nullable=true)
     */
    protected $installScript;

    /**
     * @var string
     *
     * @ORM\Column(name="sql_file", type="string", length=100)
     */
    protected $sqlFile;

    /**
     * @var string
     *
     * @ORM\Column(name="sql_user", type="string", length=100)
     */
    protected $sqlUser;

    /**
     * @var string
     *
     * @ORM\Column(name="config_script", type="text")
     */
    protected $configScript;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=100)
     */
    protected $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="git_path", type="string", length=200)
     */
    protected $gitPath;

    /**
     * @var string
     *
     * @ORM\Column(name="git_login", type="string", length=100)
     */
    protected $gitLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="git_pass", type="string", length=100)
     */
    protected $gitPass;

    /**
     * @ORM\OneToMany(targetEntity="Checklist", mappedBy="project", cascade={"persist"})
     */
    protected $checklists;

    /**
     * @ORM\OneToMany(targetEntity="Instance", mappedBy="project", cascade={"persist"})
     */
    protected $instances;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->checklists = new ArrayCollection();
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
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstallScript()
    {
        return $this->installScript;
    }

    /**
     * @param string $installScript
     *
     * @return Project
     */
    public function setInstallScript($installScript)
    {
        $this->installScript = $installScript;

        return $this;
    }

    /**
     * @return string
     */
    public function getSqlFile()
    {
        return $this->sqlFile;
    }

    /**
     * @param string $sqlFile
     *
     * @return Project
     */
    public function setSqlFile($sqlFile)
    {
        $this->sqlFile = $sqlFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getSqlUser()
    {
        return $this->sqlUser;
    }

    /**
     * @param string $sqlUser
     *
     * @return Project
     */
    public function setSqlUser($sqlUser)
    {
        $this->sqlUser = $sqlUser;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfigScript()
    {
        return $this->configScript;
    }

    /**
     * @param string $configScript
     *
     * @return Project
     */
    public function setConfigScript($configScript)
    {
        $this->configScript = $configScript;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     *
     * @return Project
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return string
     */
    public function getGitPath()
    {
        return $this->gitPath;
    }

    /**
     * @param string $gitPath
     *
     * @return Project
     */
    public function setGitPath($gitPath)
    {
        $this->gitPath = $gitPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getGitLogin()
    {
        return $this->gitLogin;
    }

    /**
     * @param string $gitLogin
     *
     * @return Project
     */
    public function setGitLogin($gitLogin)
    {
        $this->gitLogin = $gitLogin;

        return $this;
    }

    /**
     * @return string
     */
    public function getGitPass()
    {
        return $this->gitPass;
    }

    /**
     * @param string $gitPass
     *
     * @return Project
     */
    public function setGitPass($gitPass)
    {
        $this->gitPass = $gitPass;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChecklists()
    {
        return $this->checklists;
    }

    /**
     * @param Checklist $item
     *
     * @return Project
     */
    public function addChecklist(Checklist $item)
    {
        $this->checklists[] = $item;

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