<?php
namespace App\Factories\Form;

use App\Components\Form\FilterTasks;

interface IFilterTasksFactory
{
    /**
     * @return FilterTasks
     */
    public function create();
}
