<?php

namespace Kanboard\Plugin\Creecros_Filter_Pack\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseDateFilter;
use Kanboard\Model\TaskModel;
use PicoDb\Table;
use PicoDb\Database;

/**
 * Filter by due date with null
 *
 * @package filter
 * @author  Craig Crosby
 */
class DateWithNull extends BaseDateFilter implements FilterInterface
{
const TABLE = 'tasks';
    /**
     * Database object
     *
     * @access private
     * @var Database
     */
    private $db;
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('date_withnull');
    }
    /**
     * Set database object
     *
     * @access public
     * @param  Database $db
     * @return $this
     */
    public function setDatabase(Database $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {        
            
            $method = $this->parseOperator();
            $timestamp = $this->dateParser->getTimestampFromIsoFormat($this->value);
            
            if ($method !== '') {
                $duedate = $this->db
                ->table(self::TABLE)
                ->beginOr()
                ->eq('date_due', 0)
                ->$method('date_due', $this->getTimestampFromOperator($method, $timestamp))
                ->closeOr()
                ->findAllByColumn('id');
            } 
        
        if (isset($duedate) && !empty($duedate)) { return $this->query->in(TaskModel::TABLE.'.id', $duedate); } else { return $this->query->in(TaskModel::TABLE.'.id', [0]); }
    }
}
