<?php
namespace BuilderBundle\Services;

/**
 * Class FileFinderService
 * @package BuilderBundle\Services
 */
class FileFinderService
{
    /**
     * @var string
     */
    private  $webRoot;

    /**
     * FileFinderService constructor.
     * @param $rootDir
     */
    public function __construct($rootDir)
    {
        $this->webRoot = realpath($rootDir . '/../web/');
    }

    /**
     * @param string $directoryName
     *
     * @return array
     */
    public function getFilesFromPath($directoryName)
    {
        $files = [];
        $dir = $this->webRoot."/".$directoryName."/";
        $allFiles = scandir($dir);
        foreach($allFiles as $file) {
            if (is_file($dir.$file)) {
                $files[] = $file;
            }
        }

        return $files;
    }
}