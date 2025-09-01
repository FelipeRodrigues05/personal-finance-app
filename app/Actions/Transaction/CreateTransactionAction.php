<?php declare(strict_types = 1);

namespace App\Actions\Transaction;

use App\Enums\TransactionTypeEnum;
use App\Jobs\CreateTransactionRecurrentJob;
use App\Models\Card;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

final class CreateTransactionAction
{
    public static function handle(Collection $data): void
    {
        $transactionType = $data->get('type') === '1' ? TransactionTypeEnum::INCOME : TransactionTypeEnum::EXPENSE;

        /** @var Card $card */
        $card = Card::query()->find($data->get('card')) ?? null;

        Transaction::query()->create([
            'value'            => $data->get('value'),
            'category_id'      => Category::query()->findOrFail($data->get('category'))->id,
            'user_id'          => auth()->user()->id,
            'card_id'          => $card?->id,
            'description'      => $data->get('description'),
            'type'             => $transactionType,
            'transaction_date' => $data->get('transaction_date'),
            'image_path'       => Storage::disk('s3')->url($data->get('attachment')),
            'used_card'        => (bool) $card,
            'is_recurrent'     => $data->get('is_recurrent'),
        ]);

        if ($data->get('used_card') and $transactionType === TransactionTypeEnum::EXPENSE) {
            $card->update([
                'used' => $card->used + $data->get('value'),
            ]);
        }
    }

    private function scheduleRecurringTransaction(array $data): void
    {
        $nextDate = Carbon::parse($data['transaction_date'])->addMonthNoOverflow();
        // Queue a job to create the next transaction on the due date
        Queue::later($nextDate, new CreateTransactionRecurrentJob($data));

        Log::info('Scheduled recurring transaction for: ' . $nextDate->toDateString(), $data);
    }
}
