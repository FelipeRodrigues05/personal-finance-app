<?php declare(strict_types = 1);

namespace App\Jobs;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class CreateTransactionRecurrentJob implements ShouldQueue
{
    use Queueable;

    protected Collection $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $transaction = Transaction::query()->create([
            'value'            => $this->data->get('value'),
            'transaction_date' => Carbon::parse($this->data->get('transaction_date'))->addMonthNoOverflow(),
            'type'             => $this->data->get('type'),
            'category_id'      => $this->data->get('category'),
            'description'      => $this->data->get('description'),
            'is_recurrent'     => true,
            'used_card'        => $this->data->get('used_card') ?? false,
        ]);

        Log::info('Created recurring transaction: ' . $transaction->id);
    }
}
