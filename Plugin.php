<?php

namespace Kanboard\Plugin\Task_Subtask_Assignee_Filter;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\Task_Subtask_Assignee_Filter\Filter\Task_Subtask_Assignee;
use PicoDb\Table;

class Plugin extends Base
{
    public function initialize()
    {
            
        //Filter
        $this->container->extend('taskLexer', function($taskLexer, $c) {
            $taskLexer->withFilter(Task_Subtask_Assignee::getInstance()->setCurrentUserId($c['userSession']->getId()));
            return $taskLexer;
        });

    }
    
    public function getPluginName()
    {
        return 'Task_Subtask_Assignee_Filter';
    }
    public function getPluginDescription()
    {
        return t('Filter');
    }
    public function getPluginAuthor()
    {
        return 'Craig Crosby';
    }
    public function getPluginVersion()
    {
        return '0.0.1';
    }
    public function getPluginHomepage()
    {
        return 'https://github.com/creecros/Task_Subtask_Assignee_Filter';
    }
}
