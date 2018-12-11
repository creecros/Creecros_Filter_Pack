# :toilet: Creecros's Filter Pack

### :raising_hand: Make a request if you need new filters.

### :nail_care: Task Subtask Assignee Filter

- Use `task_subtask_assignee:name` to filter for both tasks and subtasks assigned to `name`, add `status:open` at the end to exclude closed tasks.

### :paperclip: Chainable Subtask Assignee Filter

- Use `subtask_assignee:name` to filter subtasks assigned to `name`, chainable to other filters.

### :japanese_ogre: Subtask Status Filter

- Use `subtask:status:DONE` or `subtask:status:2` to filter for subtasks that are `DONE`
- Use `subtask:status:TODO` or `subtask:status:0` to filter for subtasks that are `TODO`
- Use `subtask:status:INPROGRESS` or `subtask:status:1` to filter for subtasks that are `INPROGRESS`
- Use `subtask:status:RUNNING` to filter for subtasks that are `RUNNING`, regardless of `STATUS`

## :calendar: Search for tasks by due date and include tasks with empty due date

- Use `date_withnull:<=today` or `date_withnunll:>=YYYY-MM-DD` to filter for tasks by due date and include empty due dates in search
  - The date must use the ISO 8601 format: YYYY-MM-DD.

### :family: Combinable

Example:
- `task_subtask_assignee:name subtask:status:RUNNING status:open`

