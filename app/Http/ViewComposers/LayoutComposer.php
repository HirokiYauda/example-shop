<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\View\View;

/**
 * Class LayoutComposer
 */
class LayoutComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with([
            'categories' => Category::all(),
        ]);
    }

}