<?php
namespace App\Presenters;

/**
 * Class TaskPresenter
 * @package App\Presenters
 */
class TaskPresenter extends BasePresenter
{
    /** @var \App\Model\Repository\TaskGroupRepository @inject */
    public $taskGroupRepository;
    /** @var \App\Model\Repository\TaskRepository @inject */
    public $taskRepository;
    /** @var \App\Factories\Modal\IInsertTaskGroupFactory @inject */
    public $insertTaskGroupFactory;
    /** @var \App\Factories\Form\IInsertTaskFactory @inject */
    public $insertTaskFactory;
    /** @var \App\Factories\Form\IFilterTasksFactory @inject */
    public $filterTasksFactory;
    /**
	 * @var number
	 * @persistent
	 */
    public $idTaskGroup;
    /**
	 * @var array
	 * @persistent
	 */
    public $filter = array();

    public function renderDefault()
    {
        $this->template->taskGroups = $this->getTaskGroups();
    }

    /**
     * @param int $id
     */
    public function handleCompleteTask($id)
    {
		$task = $this->taskRepository->getById($id);
		if($task){
			$task->setCompleted(TRUE);
			$this->taskRepository->updateEntity($task);
			$this->flashMessage('Task was completed', 'success');
		} else {
			$this->flashMessage('Task was not found', 'error');
		}
		
		$this->redrawOrRedirect(array('tasks', 'flashes'));
    }

    /**
     * @param int $id
     */
    public function handleDeleteTaskGroup($id)
    {
        $this->taskGroupRepository->delete($id);
		$this->redrawOrRedirect('taskGroups');
    }

    /**
     * @param number $idTaskGroup
     */
    public function renderTaskGroup($idTaskGroup)
    {
        $this->idTaskGroup = $idTaskGroup;
        $this->template->tasks = $this->getTasks($idTaskGroup);
    }

    /**
     * @return \App\Components\Modal\InsertTaskGroup
     */
    protected function createComponentInsertTaskGroupModal()
    {
        $control = $this->insertTaskGroupFactory->create();
        return $control;
    }

    /**
     * @return \App\Components\Form\InsertTask
     */
    protected function createComponentInsertTaskForm()
    {
        $control = $this->insertTaskFactory->create();
        $control->setTaskGroupId($this->idTaskGroup);
        return $control;
    }

    /**
     * @return \App\Components\Form\FilterTasks
     */
    protected function createComponentFilterTasksForm()
    {
        $control = $this->filterTasksFactory->create();
        $control->setTaskGroupId($this->idTaskGroup);
		$control->setFilter($this->filter);
        return $control;
    }

    /**
     * @return array
     */
    protected function getTaskGroups()
    {
        $result = array();
        $taskGroups = $this->taskGroupRepository->getAll();
        foreach ($taskGroups as $taskGroup) {
            $item = array();
            $item['id'] = $taskGroup->getId();
            $item['name'] = $taskGroup->getName();
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @param number $idTaskGroup
     * @return array
     */
    protected function getTasks($idTaskGroup)
    {
        $result = array();
		$criteria = array_merge(
			$this->prepareTasksFilter(),
			array('taskGroup' => $idTaskGroup)
		);
		
        $tasks = $this->taskRepository->getBy($criteria, array('date' => 'DESC'));
        foreach ($tasks as $task) {
            $item = array();
            $item['id'] = $task->getId();
            $item['date'] = $task->getDate();
            $item['name'] = $task->getName();
            $item['completed'] = $task->getCompleted();
            $result[] = $item;
        }
        return $result;
    }
	
	
	/**
	 * @param string|array $snippets
	 * @param string $destination
	 */
	public function redrawOrRedirect($snippets, $destination = 'this')
    {
    	if($this->isAjax()){
			if(!is_array($snippets)){
				$snippets = array($snippets);
			}
			foreach($snippets as $snippet){
				$this->redrawControl($snippet);
			}
		} else {
			$this->redirect($destination);
		}
    }
	
	
	/**
	 * @return array
	 */
	private function prepareTasksFilter()
	{
		$filter = array();
		if(array_key_exists('name', $this->filter)){
			$filter['name LIKE'] = "%{$this->filter['name']}%";
		}
		return $filter;
	}
}
