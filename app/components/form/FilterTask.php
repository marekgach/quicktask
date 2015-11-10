<?php
namespace App\Components\Form;

use App\Model\Entity\Task;
use App\Model\Entity\TaskGroup;
use App\Model\Repository\TaskRepository;
use App\Model\Repository\TaskGroupRepository;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class FilterTask extends Control
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
        $template->setFile(__DIR__ . '/templates/FilterTask.latte');
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
    protected function createComponentFilterTaskForm()
    {

        $searchSession = $this->presenter->getSession('serach');
        $word = $searchSession->searchString;

        $form = new Form();
        $form->getElementPrototype()->class('newTaskForm ajax');
        $form->addText('search', 'Search')
             ->setDefaultValue($word);
        $form->addHidden('idTaskGroup', $this->idTaskGroup);
        $form->addSubmit('submit', 'Filtruj');
        $form->onSuccess[] = array($this, 'filterTaskFromSuccess');
        return $form;
    }

    /**
     * @param Form $form
     * @param $values
     */
    public function filterTaskFromSuccess(Form $form, $values)
    {
        
        $searchSession = $this->presenter->getSession('serach');
        $searchSession->searchString = $values->search;

        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('tasks');
        } else {
            $this->redirect('this');
        }
        
    }
}
