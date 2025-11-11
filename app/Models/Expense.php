<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);

        static::creating(function ($expense) {
            if (!$expense->empresa_id) {
                $expense->empresa_id = Auth::user()->empresa_id;
            }
            if (!$expense->user_id) {
                $expense->user_id = Auth::id();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empresa_id',
        'expense_category_id',
        'user_id',
        'description',
        'amount',
        'expense_date',
        'receipt_number',
        'attachment_path',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    /**
     * Get the empresa that owns the expense.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Get the category that owns the expense.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    /**
     * Get the user that created the expense.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
