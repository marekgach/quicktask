<?php
namespace App\Components\Form;

use App\Model\Entity\Task;
use App\Model\Entity\TaskGroup;
use App\Model\Repository\TaskRepository;
use App\Model\Repository\TaskGroupRepository;
use App\Presenters\TaskPresenter;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class InsertTask extends Control
{
    /** @var TaskRepository*/
    public $taskRepository;
    /** @var TaskGroupRepository*/
    public $taskGroupRepository;
    /** @var number */
    public $idTaskGroup;

    /**
     * @param TaskRepository $taskRepository
     * @param TaskGroupRepository $taskGroupRepository
     */
    public function __construct(TaskRepository $taskRepository, TaskGroupRepository $taskGroupRepository)
    {
        parent::__construct();
        $this->taskRepository = $taskRepository;
        $this->taskGroupRepository = $taskGroupRepository;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/templates/InsertTask.latte');
        $template->render();
    }

    /**
     * @param int $idTaskGroup
     */
    public function setTaskGroupId($idTaskGroup)
    {
        $this->idTaskGroup = $idTaskGroup;
    }

    /**
     * @return Form
     */
    protected function createComponentInsertTaskForm()
    {
        $form = new Form();
        $form->addText('name', 'Name')
            ->setRequired('Please fill task name');
        $form->addText('date', 'Date')
            ->setRequired('Please fill task date');
        $form->addSelect('taskGroup', 'Task group', $this->taskGroupRepository->getSelect())
            ->setRequired('Please select task group')
			->setDefaultValue($this->idTaskGroup);
        $form->addSubmit('submit', 'Add');
        $form->onSuccess[] = array($this, 'insertTaskFormSuccess');
        return $form;
    }

    /**
     * @param Form $form
     * @param $values
     */
    public function insertTaskFormSuccess(Form $form, $values)
    {
        $taskGroup = $this->taskGroupRepository->getById($values->taskGroup);

        $taskEntity = new Task();
        $taskEntity->setName($values->name);
        $taskEntity->setDate($values->date);
        $taskEntity->setTaskGroup($taskGroup);
        $this->taskRepository->insert($taskEntity);
		
		if($this->presenter->isAjax()){
			$form->setValues(array('taskGroup' => $this->idTaskGroup), TRUE);
			$this->redrawControl();
		}
	
		/** @var TaskPresenter $presenter */
		$presenter = $this->presenter;
        $presenter->flashMessage('Task was created', 'success');
		$presenter->redrawOrRedirect(array('flashes', 'tasks'));
    }
}
