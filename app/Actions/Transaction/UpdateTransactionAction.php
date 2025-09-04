<?php

namespace App\Actions\Transaction;

use App\Enums\TransactionTypeEnum;
use App\Models\Card;
use App\Models\Category;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Collection;

class UpdateTransactionAction
{
    /**
     * @throws Exception
     */
    public static function handle(Collection $data): void
    {
        /** @var Card $card */
        $card = Card::query()->fromUser()->find($data->get('card_id')) ?? null;

        $transaction = Transaction::query()->find($data->get('id'));

        $transaction->update([
            'value'            => $data->get('value'),
            'category_id'      => Category::query()->findOrFail($data->get('category_id'))->id,
            'user_id'          => auth()->user()->id,
            'card_id'          => $card?->id,
            'description'      => $data->get('description'),
            'type'             => $data->get('type'),
            'transaction_date' => $data->get('transaction_date'),
            'image_path'       => !is_null($data->get('attachment')) ?: $transaction->image_path,
            'used_card'        => (bool) $card,
            'is_recurrent'     => $data->get('is_recurrent'),
        ]);

        if($card) {
            $amount = match ($data->get('type')) {
                TransactionTypeEnum::EXPENSE->value => $card->used + $data->get('value'),
                TransactionTypeEnum::INCOME->value => $card->used - $data->get('value'),
                default => throw new Exception('Unexpected match value'),
            };

            $card->update([
                'used' => $amount,
            ]);
        }
    }
}
