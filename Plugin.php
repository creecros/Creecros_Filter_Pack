<?php

namespace Kanboard\Plugin\Creecros_Filter_Pack;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\Creecros_Filter_Pack\Filter\Task_Subtask_Assignee;
use Kanboard\Plugin\Creecros_Filter_Pack\Filter\SubtaskStatus;
use PicoDb\Table;

class Plugin extends Base
{
    public function initialize()
    {
            
        //Task_Subtask_Assignee Filter
        $this->container->extend('taskLexer', function($taskLexer, $c) {
            $taskLexer->withFilter(Task_Subtask_Assignee::getInstance()
                    ->setCurrentUserId($c['userSession']->getId())
                    ->setDatabase($c['db']));
            return $taskLexer;
        });
        
        //SubtaskStatus Filter
        $this->container->extend('taskLexer', function($taskLexer, $c) {
            $taskLexer->withFilter(SubtaskStatus::getInstance()
                    ->setCurrentUserId($c['userSession']->getId())
                    ->setDatabase($c['db']));
            return $taskLexer;
        });

    }
    
    public function getPluginName()
    {
        return 'Creecros_Filter_Pack';
    }
    public function getPluginDescription()
    {
        return t('creecros\'s filter pack');
    }
    public function getPluginAuthor()
    {
        return 'Craig Crosby';
    }
    public function getPluginVersion()
    {
        return '1.1.0';
    }
    public function getPluginHomepage()
    {
        return 'https://github.com/creecros/Creecros_Filter_Pack';
    }
}
