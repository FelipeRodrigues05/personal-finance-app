<?php declare(strict_types = 1);

namespace App\Actions\Card;

use App\Enums\CardType;
use App\Models\Card;
use Illuminate\Support\Collection;

final class CreateCardAction
{
    public static function handle(Collection $data): void
    {
        $cardType = $data->get('type') == 0 ? CardType::CREDIT : CardType::DEBIT;

        Card::query()->create([
            'user_id' => auth()->user()->id,
            'name'    => $data->get('name'),
            'limit'   => $data->get('limit'),
            'type'    => $cardType,
            'color'   => $data->get('color'),
        ]);
    }
}
