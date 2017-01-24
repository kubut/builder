<?php
namespace BuilderBundle\Services;

use BuilderBundle\Entity\Instance;
use BuilderBundle\Entity\Project;
use BuilderBundle\Model\ProjectModel;

/**
 * Class JiraService
 * @package BuilderBundle\Services
 */
class JiraService
{
    /** @var  ProjectModel $projectModel */
    protected $projectModel;

    /**
     * JiraService constructor.
     * @param ProjectModel $projectModel
     */
    public function __construct(ProjectModel $projectModel)
    {
        $this->projectModel = $projectModel;
    }

    /**
     * @param $projectId
     * @return array
     */
    public function getJiraConfiguration($projectId)
    {
        /** @var Project $project */
        $project = $this->projectModel->getProject($projectId);

        return [
            "url" => !is_null($project->getJiraUrl()) ? $project->getJiraUrl() : '',
            "projectSymbol" => !is_null($project->getJiraPrefix()) ? $project->getJiraPrefix() : '',
            "password" => !is_null($project->getJiraPass()) ? $project->getJiraPass() : '',
            "login" => !is_null($project->getJiraLogin()) ? $project->getJiraLogin() : '',
        ];
    }

    /**
     * @param integer $projectId
     * @param array $inputData
     */
    public function saveJiraConfiguration($projectId, array  $inputData)
    {
        $updateParams = [
            "jiraUrl" => $inputData['url'],
            "jiraLogin" => $inputData['login'],
            "jiraPass" => $inputData['password'],
            "jiraPrefix" => $inputData['projectSymbol'],
        ];
        $project = $this->projectModel->getProject($projectId);
        $this->projectModel->update($project, $updateParams);
    }

    /**
     * @param Instance $instance
     * @return array
     */
    public function getTaskInfo(Instance $instance)
    {
        /** @var Project $project */
        $project = $instance->getProject();
        $taskPrefix = $project->getJiraPrefix();
        $branchName = $instance->getBranch();
        $result = [];

        if (strpos($branchName, $taskPrefix) !== false) {
            $taskName = $branchName;
            $username = $project->getJiraLogin();
            $password = $project->getJiraPass();
            $url = $project->getJiraUrl();

            $apiUrl = $url . "/rest/api/2/issue/" . $taskName;
            $ch = curl_init();
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json'
            );

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

            $ch_error = curl_error($ch);
            if ($ch_error) {
                    return $result;
            } else {
                $jiraResponse = curl_exec($ch);
                $array = json_decode($jiraResponse, true);
                if (isset($array['fields'])) {
                    $result = [
                        'title' => $array['fields']['summary'],
                        'reporter' => $array['fields']['reporter']['displayName'],
                        'description' => $array['fields']['description'],
                        'url' => $url . "/browse/" . $taskName
                    ];
                }
                curl_close($ch);
            }
        }
        return $result;
    }
}