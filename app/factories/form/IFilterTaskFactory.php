<?php
namespace App\Factories\Form;

use App\Components\Form\FilterTask;

interface IFilterTaskFactory
{
    /**
     * @return FilterTask
     */
    public function create();
}
