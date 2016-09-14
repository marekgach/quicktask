<?php
namespace App\Model\Repository;

use App\Model\Entity;
use Kdyby\Doctrine\EntityManager;

class TaskGroupRepository extends AbstractRepository
{
    /** @var \Kdyby\Doctrine\EntityRepository */
    private $taskGroup;

    /** @var \Kdyby\Doctrine\EntityRepository */
    private $task;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->taskGroup = $this->entityManager->getRepository(Entity\TaskGroup::getClassName());
        $this->task = $this->entityManager->getRepository(Entity\Task::getClassName());
    }

    /**
     * @param number $id
     * @return Entity\TaskGroup|null
     */
    public function getById($id)
    {
        return $this->taskGroup->find($id);
    }

    /**
     * @return Entity\TaskGroup[]
     */
    public function getAll()
    {
        return $this->taskGroup->findBy(array());
    }

    /**
     * @param Entity\TaskGroup $taskGroup
     */
    public function insert(Entity\TaskGroup $taskGroup)
    {
        $this->entityManager->persist($taskGroup);
        $this->entityManager->flush();
    }

    /**
     * @param number $id
     * @param number $category
     */
    public function setCategory($category, $id)
    {
        $task = $this->task->find($id);
        $task->setTaskGroup($this->getById($category));
        $this->entityManager->flush();
    }
}
