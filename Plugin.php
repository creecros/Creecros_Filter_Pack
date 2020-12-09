<?php

namespace Kanboard\Plugin\Creecros_Filter_Pack;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\Creecros_Filter_Pack\Filter\Tags;
use Kanboard\Plugin\Creecros_Filter_Pack\Filter\Task_Subtask_Assignee;
use Kanboard\Plugin\Creecros_Filter_Pack\Filter\SubtaskStatus;
use Kanboard\Plugin\Creecros_Filter_Pack\Filter\SubtaskAssignee;
use Kanboard\Plugin\Creecros_Filter_Pack\Filter\DateWithNull;
use PicoDb\Table;

class Plugin extends Base
{
    public function initialize()
    {
        //DateWithNull Filter
        $this->container->extend('taskLexer', function($taskLexer, $c) {
            $taskLexer->withFilter(DateWithNull::getInstance()->setDatabase($c['db'])
                                                              ->setDateParser($c['dateParser']));
            return $taskLexer;
        });
        
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

        //SubtaskAssignee Filter
        $this->container->extend('taskLexer', function($taskLexer, $c) {
            $taskLexer->withFilter(SubtaskAssignee::getInstance()
                    ->setCurrentUserId($c['userSession']->getId())
                    ->setDatabase($c['db']));
            return $taskLexer;
        });

        //Tags Filter
        $this->container->extend('taskLexer', function($taskLexer, $c) {
            $taskLexer->withFilter(Tags::getInstance()
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
        return '1.3.1';
    }
    public function getPluginHomepage()
    {
        return 'https://github.com/creecros/Creecros_Filter_Pack';
    }
}
