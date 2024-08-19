<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable{
    use Queueable, SerializesModels;
    public $name;
    public $orderId;
    public $date;
    public $product;
    public $amount;
    public function __construct($name, $orderId, $date, $product, $amount){
        $this->name = $name;
        $this->orderId = $orderId;
        $this->date = $date;
        $this->product = $product;
        $this->amount = $amount;

    }
    public function build()
    {
        return $this->subject('Order Confirmation')->view('emails.orderConfirmation');
    }
}

