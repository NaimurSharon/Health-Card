<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoCallPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
        'payment_data'
    ];

    protected $casts = [
        'payment_data' => 'array',
    ];

    public function consultation()
    {
        return $this->belongsTo(VideoConsultation::class);
    }
}