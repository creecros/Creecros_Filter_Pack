<?php

namespace Kanboard\Plugin\Creecros_Filter_Pack\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseFilter;
use Kanboard\Model\TagModel;
use Kanboard\Model\TaskModel;
use Kanboard\Model\TaskTagModel;
use PicoDb\Database;
use PicoDb\Table;


class Tags extends BaseFilter implements FilterInterface
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
        return array('tags');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return string
     */
    public function apply()
    {
        $values = explode(",", $this->value);
        if ($this->value === 'none') {
            $sub_query = $this->getQueryOfTaskIdsWithoutTags();
            $this->query->inSubquery(TaskModel::TABLE.'.id', $sub_query);
            return $this;
        } else {
            foreach ($values as $value) {
                $sub_query = $this->getQueryOfTaskIdsWithGivenTag($value);
                $this->query->inSubquery(TaskModel::TABLE.'.id', $sub_query);
            }
            return $this;
        }
}

    protected function getQueryOfTaskIdsWithoutTags()
    {
        return $this->db
            ->table(TaskModel::TABLE)
            ->columns(TaskModel::TABLE . '.id')
            ->asc(TaskModel::TABLE . '.project_id')
            ->left(TaskTagModel::TABLE, 'tg', 'task_id', TaskModel::TABLE, 'id')
            ->isNull('tg.tag_id');
    }

    protected function getQueryOfTaskIdsWithGivenTag($value)
    {
        return $this->db
            ->table(TagModel::TABLE)
            ->columns(TaskTagModel::TABLE.'.task_id')
            ->ilike(TagModel::TABLE.'.name', $value)
            ->asc(TagModel::TABLE.'.project_id')
            ->join(TaskTagModel::TABLE, 'tag_id', 'id');
    }
}
