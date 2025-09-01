<?php declare(strict_types = 1);

namespace App\Jobs;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

final class CreateTransactionRecurrentJob implements ShouldQueue
{
    use Queueable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $transaction = Transaction::query()->create([
            'value'            => $this->data['value'],
            'transaction_date' => Carbon::parse($this->data['transaction_date'])->addMonthNoOverflow(),
            'type'             => $this->data['type'],
            'category_id'      => $this->data['category'],
            'description'      => $this->data['description'],
            'is_recurrent'     => true,
            'used_card'        => $this->data['used_card'] ?? false,
        ]);

        Log::info('Created recurring transaction: ' . $transaction->id);
    }
}
