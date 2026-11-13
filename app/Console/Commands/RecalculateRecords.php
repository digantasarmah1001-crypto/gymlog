<?php

namespace App\Console\Commands;

use App\Actions\RecalculatePersonalRecords;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('gymlog:recalculate-records')]
#[Description('Mark the personal records (is_pr) of all logged sets again')]
class RecalculateRecords extends Command
{
    public function handle(RecalculatePersonalRecords $recalculate): int
    {
        $records = $recalculate->handle();

        $this->info("Done: {$records} sets are a personal record.");

        return self::SUCCESS;
    }
}
