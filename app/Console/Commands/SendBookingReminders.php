<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\BookingReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    protected $signature = 'reminders:send';

    protected $description = 'Send booking reminders to customers';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tomorrow = Carbon::now()->addDay()->format('Y-m-d');
        $bookings = Booking::whereDate('startTime', $tomorrow)->get();

        foreach ($bookings as $booking) {
            Mail::to($booking->customer->email)->queue(new BookingReminderMail($booking));
        }

        if ($this->output) {
            $this->info('Booking reminders have been sent successfully.');
        }
    }
}
