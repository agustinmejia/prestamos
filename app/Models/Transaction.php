<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = ['transaction', 'type', 'deleted_at'];

    public function payments() {
        return $this->hasMany(LoanDayAgent::class, 'transaction_id');
    }
}
