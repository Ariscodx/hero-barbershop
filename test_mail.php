<?php
use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('This is a test email from Hero Barbershop.', function ($msg) {
        $msg->to('rizalcok38@gmail.com')
            ->subject('Test SMTP Hero Barbershop');
    });
    echo "SUCCESS: Email sent successfully!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
