<?php

namespace Kanboard\Plugin\Creecros_Filter_Pack\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseFilter;
use Kanboard\Model\TaskModel;
use Kanboard\Model\UserModel;
use Kanboard\Model\SubtaskModel;
use PicoDb\Database;
use PicoDb\Table;


class Task_Subtask_Assignee extends BaseFilter implements FilterInterface
{

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
     * @return TaskAssigneeFilter
     */
    public function setCurrentUserId($userId)
    {
        $this->currentUserId = $userId;
        return $this;
    }
    
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
        return array('task_subtask_assignee');
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
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(TaskModel::TABLE.'.owner_id', $this->value);
        } else {
            switch ($this->value) {
                case 'me':
                    $this->query->beginOr();
                    $this->query->eq(TaskModel::TABLE.'.owner_id', $this->currentUserId);
                    $this->query->in(TaskModel::TABLE.'.id', $task_ids);
                    $this->query->closeOr();
                    break;
                case 'nobody':
                    $this->query->beginOr();
                    $this->query->eq(TaskModel::TABLE.'.owner_id', 0);
                    $this->query->in(TaskModel::TABLE.'.id', $task_ids);
                    $this->query->closeOr();
                    break;
                default:
                    $this->query->beginOr();
                    $this->query->ilike(UserModel::TABLE.'.username', '%'.$this->value.'%');
                    $this->query->ilike(UserModel::TABLE.'.name', '%'.$this->value.'%');
                    $this->query->in(TaskModel::TABLE.'.id', $task_ids);
                    $this->query->closeOr();
            }
        }
        
    }

    
    protected function getSubQuery()
    {
        $subquery = $this->db->table(SubtaskModel::TABLE)
            ->columns(
                SubtaskModel::TABLE.'.user_id',
                SubtaskModel::TABLE.'.task_id',
                UserModel::TABLE.'.name',
                UserModel::TABLE.'.username'
            )
            ->join(UserModel::TABLE, 'id', 'user_id', SubtaskModel::TABLE);
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
            $subquery->eq(SubtaskModel::TABLE.'.user_id', $this->value);
        } else {
            switch ($this->value) {
                case 'me':
                    $subquery->eq(SubtaskModel::TABLE.'.user_id', $this->currentUserId);
                    break;
                case 'nobody':
                    $subquery->eq(SubtaskModel::TABLE.'.user_id', 0);
                    break;
                default:
                    $subquery->beginOr();
                    $subquery->ilike(UserModel::TABLE.'.username', $this->value.'%');
                    $subquery->ilike(UserModel::TABLE.'.name', '%'.$this->value.'%');
                    $subquery->closeOr();
            }
        }
        return $subquery;
    }

    
}
