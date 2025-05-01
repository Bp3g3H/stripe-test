<?php

namespace App\Models;

use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_sum',
        'status',
        'completed_at',
    ];

       /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => CartStatus::class, // Cast status to the CartStatus enum
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // /**
    //  * Get the items associated with the cart.
    //  */
    // public function items(): HasMany
    // {
    //     return $this->hasMany(CartItem::class); // Assuming you have a `CartItem` model
    // }
}
