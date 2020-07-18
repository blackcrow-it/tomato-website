<?php

namespace App\ViewComposers;

use App\Category;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $view->with('mainMenu', $this->getMainMenu());
    }

    private function getMainMenu() {
        return Category::orderBy('title', 'asc')
            ->get()
            ->toTree();
    }
}
