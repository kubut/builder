<?php
namespace BuilderBundle\Model;

use BuilderBundle\Entity\Database;
use BuilderBundle\Entity\Project;
use BuilderBundle\Factory\DatabaseFactory;
use BuilderBundle\Repository\DatabaseRepository;

/**
 * Class DatabaseModel
 * @package BuilderBundle\Model
 */
class DatabaseModel
{
    /** @var DatabaseFactory $databaseFactory */
    private $databaseFactory;

    /** @var  DatabaseRepository $databaseRepository */
    private $databaseRepository;

    /**
     * DatabaseModel constructor.
     * @param DatabaseFactory $databaseFactory
     * @param DatabaseRepository $databaseRepository
     */
    public function __construct(
        DatabaseFactory $databaseFactory,
        DatabaseRepository $databaseRepository
    )
    {
        $this->databaseFactory = $databaseFactory;
        $this->databaseRepository = $databaseRepository;
    }

    /**
     * @param Project $project
     * @param string  $name
     * @param string  $comment
     *
     * @return mixed
     */
    public function createDatabaseForProject(Project $project, $name, $comment)
    {
        $factoryParams = [
            'project' => $project,
            'name' => $name,
            'comment' => $comment
        ];

        $database =  $this->databaseFactory->createFromArray($factoryParams);
        $this->databaseRepository->save($database);

        return $database;
    }

    /**
     * @param $database
     */
    public function update($database)
    {
        $this->databaseRepository->save($database);
    }

    /**
     * @param integer $databaseId
     * @return Database
     * @throws \Exception
     */
    public function getById($databaseId)
    {
        return $this->databaseRepository->findById($databaseId);
    }

    public function fetchAllDatabases($projectId)
    {
        return $this->databaseRepository->fetchAllDatabasesForProject($projectId);
    }

    public function delete($databaseId)
    {
        $database = $this->getById($databaseId);
        $this->databaseRepository->delete($database);
    }
}