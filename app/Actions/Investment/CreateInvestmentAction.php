<?php declare(strict_types = 1);

namespace App\Actions\Investment;

use App\Models\Investment;
use Illuminate\Support\Collection;

final class CreateInvestmentAction
{
    public static function handle(Collection $data): Investment
    {
        return Investment::query()->create([
            'name'        => $data->get('name'),
            'amount'      => $data->get('amount'),
            'recurrence'  => $data->get('recurrence'),
            'description' => $data->get('description'),
            'tax'         => $data->get('tax'),
            'user_id'     => auth()->user()->id,
        ]);
    }
}
