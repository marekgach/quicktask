<?php
namespace App\Components\Form;

use App\Model\Entity\Task;
use App\Model\Entity\TaskGroup;
use App\Model\Repository\TaskRepository;
use App\Model\Repository\TaskGroupRepository;
use App\Presenters\TaskPresenter;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class FilterTasks extends Control
{
    /** @var TaskRepository*/
    public $taskRepository;
	/** @var number */
    public $idTaskGroup;
	/** @var array */
    public $filter = array();

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        parent::__construct();
        $this->taskRepository = $taskRepository;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/templates/FilterTasks.latte');
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
     * @param array $filter
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return Form
     */
    protected function createComponentFilterTasksForm()
    {
        $form = new Form();
        $form->addText('name', 'Name');
        $form->addSubmit('submit', 'Filter');
        $form->onSuccess[] = array($this, 'filterTasksFormSuccess');
		
		$form->setDefaults($this->filter);
		
        return $form;
    }

    /**
     * @param Form $form
     * @param $values
     */
    public function filterTasksFormSuccess(Form $form, $values)
    {
        $this->presenter->redirect('this', array('filter' => (array) $values));
    }
}
