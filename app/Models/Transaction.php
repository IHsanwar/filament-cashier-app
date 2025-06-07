<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'date',
        'total',
    ];

    // Pastikan total diproses dengan benar
    protected $casts = [
        'total' => 'decimal:2',
        'date' => 'date',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function boot()
    {
        parent::boot();
    }

    /**
     * Recalculate total from all transaction items
     * This method should be called after all items have been saved
     */
    public function recalculateTotal(): void
    {
    
        $this->load('items');
        
        
        $total = $this->items->sum('subtotal');
        $this->newQuery()->where('id', $this->id)->update(['total' => $total]);
        
        $this->setAttribute('total', $total);
    }
}