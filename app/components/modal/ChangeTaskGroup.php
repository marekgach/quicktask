<?php
namespace App\Components\Modal;

use App\Model\Entity\TaskGroup;
use App\Model\Repository\TaskGroupRepository;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class ChangeTaskGroup extends Control
{
    /** @var TaskGroupRepository @inject*/
    public $taskGroupRepository;

    /**
     * @param TaskGroupRepository $taskGroupRepository
     */
    public function __construct(TaskGroupRepository $taskGroupRepository)
    {
        parent::__construct();
        $this->taskGroupRepository = $taskGroupRepository;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/templates/ChangeTaskGroup.latte');
        $template->render();
    }

    /**
     * @return Form
     */
    protected function createComponentChangeTaskGroupForm()
    {
        $taskGroups = $this->taskGroupRepository->getAll();
        $result = array();
        foreach ($taskGroups as $taskGroup) {
            $result[$taskGroup->getId()] = $taskGroup->getName();
        }
        
        $form = new Form();
        $form->addSelect('category', 'Category', $result);
        $form->addHidden('taskId');
        $form->addSubmit('submit', 'Save');
        $form->onSuccess[] = array($this, 'changeTaskGroupFromSuccess');
        return $form;
    }

    /**
     * @param Form $form
     * @param $values
     */
    public function changeTaskGroupFromSuccess(Form $form, $values)
    {
        
        $this->taskGroupRepository->setCategory($values->category, $values->taskId);
        $this->presenter->flashMessage('Task group was changed', 'success');
        $this->redirect('this');

    }
}
