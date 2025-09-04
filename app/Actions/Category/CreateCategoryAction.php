<?php declare(strict_types = 1);

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Collection;

final class CreateCategoryAction
{
    public static function handle(Collection $data): Category
    {
        return Category::query()->create([
            'name'    => $data->get('name'),
            'color'   => $data->get('color'),
            'user_id' => auth()->user()->id,
        ]);
    }
}
