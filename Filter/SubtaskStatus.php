<?php

namespace Kanboard\Plugin\Creecros_Filter_Pack\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseFilter;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\SubtaskTimeTrackingModel;
use Kanboard\Model\TaskModel;
use PicoDb\Database;
use PicoDb\Table;

/**
 * Filter tasks by subtasks status
 *
 * @package filter
 * @author  Craig Crosby
 */
class SubtaskStatus extends BaseFilter implements FilterInterface
{
    /**
     * Database object
     *
     * @access private
     * @var Database
     */
    private $db;

    /**
     * Current user id
     *
     * @access private
     * @var int
     */
    private $currentUserId = 0;

    /**
     * Set current user id
     *
     * @access public
     * @param  integer $userId
     * @return TaskSubtaskAssigneeFilter
     */
    public function setCurrentUserId($userId)
    {
        $this->currentUserId = $userId;
        return $this;
    }

    /**
     * Set database object
     *
     * @access public
     * @param  Database $db
     * @return TaskSubtaskAssigneeFilter
     */
    public function setDatabase(Database $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('subtask:status');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return string
     */
    public function apply()
    {
        $task_ids = $this->getSubQuery()->findAllByColumn('task_id');

        if (! empty($task_ids)) {
            $this->query->in(TaskModel::TABLE.'.id', $task_ids);
        } else {
            $this->query->eq(TaskModel::TABLE.'.id', 0); // No match
        }
    }

    /**
     * Get subquery
     *
     * @access protected
     * @return Table
     */
    protected function getSubQuery()
    {
        $subquery = $this->db->table(SubtaskModel::TABLE)
            ->columns(
                SubtaskModel::TABLE.'.status',
                SubtaskModel::TABLE.'.task_id',
                SubtaskTimeTrackingModel::TABLE.'.end'
            )
        ->join(SubtaskTimeTrackingModel::TABLE, 'subtask_id', 'id', SubtaskModel::TABLE);

        return $this->applySubQueryFilter($subquery);
    }

    /**
     * Apply subquery filter
     *
     * @access protected
     * @param  Table $subquery
     * @return Table
     */
    protected function applySubQueryFilter(Table $subquery)
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $subquery->eq(SubtaskModel::TABLE.'.status', $this->value);
        } else {
            switch ($this->value) {
                case 'DONE':
                    $subquery->eq(SubtaskModel::TABLE.'.status', 2);
                    break;
                case 'INPROGRESS':
                    $subquery->eq(SubtaskModel::TABLE.'.status', 1);
                    break;
                case 'TODO':
                    $subquery->eq(SubtaskModel::TABLE.'.status', 0);
                    break;
                case 'RUNNING':
                    $subquery->eq(SubtaskTimeTrackingModel::TABLE.'.end', 0);
                    break; 
            }
        }

        return $subquery;
    }
}
