<?php
namespace BuilderBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BaseFixture.
 */
abstract class BaseFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $fixtureFile;

    /**
     * BaseFixture constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $file = $this->translateClassToFilename($this);

        $class_info = new \ReflectionClass($this);
        $dir = dirname($class_info->getFileName());

        $value = Yaml::parse(file_get_contents($dir . "/../Yaml/" . $file));

        foreach ($value as $key => $params) {
            $object = $this->insert($params);
            $this->addReference($key, $object);
            $this->manager->persist($object);
        }

        $this->manager->flush();
    }

    /**
     * @return integer
     */
    abstract public function getOrder();

    /**
     * @param array $params
     * @return mixed
     */
    abstract public function insert($params);

    /**
     * @param $object
     * @return string
     */
    public function translateClassToFilename($object)
    {
        $classnameArray = explode("\\", get_class($object));
        $class          = array_pop($classnameArray);
        $filename       = strtolower(substr($class, 0, strpos($class, 'Data'))).'.yml';

        return $filename;
    }

    /**
     * @param array $array
     * @return array
     */
    protected function getArrayOfReferences(array $array)
    {
        $result = [];
        foreach ($array as $reference) {
            $result[] = $this->getReference($reference);
        }

        return $result;
    }
}
