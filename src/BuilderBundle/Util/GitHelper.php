<?php
namespace BuilderBundle\Util;
use BuilderBundle\Entity\Project;

/**
 * Class GitHelper
 * @package BuilderBundle\Util
 */
class GitHelper
{
    public static function createSecureGitURL(Project $project)
    {
        $gitURL = $project->getGitPath();
        $gitUsername = $project->getGitLogin();
        $gitPassword = $project->getGitPass();

        $result = parse_url($gitURL);
        return $result['scheme']."://".$gitUsername.':'.$gitPassword.'@'.$result['host'].$result['path'];
    }
}