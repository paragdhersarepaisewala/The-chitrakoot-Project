<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('events:archive-expired')]
#[Description('Scan for passed religious events, mark them expired and move them into archive')]
class ArchiveExpiredEvents extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Scanning temple events for passed dates...');

        $archivedCount = Event::autoArchiveExpired();

        if ($archivedCount > 0) {
            $this->info("Successfully expired and moved {$archivedCount} past event(s) into the archive.");
        } else {
            $this->line('No passed events found. All upcoming events are currently active.');
        }

        return Command::SUCCESS;
    }
}
