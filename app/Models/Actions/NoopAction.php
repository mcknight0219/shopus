<?php
namespace App\Models\Actions;

use App\Models\Action;

class NoopAction extends Action
{
    public function execute()
    {
    }

    /**
     * The action type identidfier
     *
     * @return String
     */
    public function getActionType()
    {
        return 'noop';
    }
}