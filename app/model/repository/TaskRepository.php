<?php
namespace App\Model\Repository;

use App\Model\Entity;
use Kdyby\Doctrine\EntityManager;

class TaskRepository extends AbstractRepository
{
    /** @var \Kdyby\Doctrine\EntityRepository */
    private $task;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->task = $this->entityManager->getRepository(Entity\Task::getClassName());
    }

    /**
     * @param number $id
     * @return Entity\Task|null
     */
    public function getById($id)
    {
        return $this->task->find($id);
    }

    /**
     * @param number $id
     * @return Entity\Task|null
     */
    public function getStateById($id)
    {
        return $this->task->find($id);
    }

    /**
     * @param number $idTaskGroup
     * @return Entity\Task[]
     */
    public function getByTaskGroup($idTaskGroup, $word = "")
    {

        $q = $this->task->createQueryBuilder('t')
            ->where('t.taskGroup = '.$idTaskGroup)
            ->andWhere('t.name LIKE :name')
            ->setParameter('name', '%'.$word.'%')
            ->orderBy('t.date', 'DESC')
            ->getQuery();
        $tasks = $q->getResult();
        return $tasks; 
        
    }

    /**
     * @param number $id
     * @param number $state
     */
    public function setState($id, $state)
    {
        $task = $this->task->find($id);
        $task->setCompleted($state);
        
        $this->entityManager->flush();
    }

    /**
     * @param Entity\Task $task
     */
    public function insert(Entity\Task $task)
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
