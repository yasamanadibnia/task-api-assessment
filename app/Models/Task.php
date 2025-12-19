<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
        ];
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Apply filters to task query.
     */
    public function scopeFilter($query)
    {
        return $query
            ->where('user_id', auth()->id())
            ->when(request('status'), fn ($q) => $q->where('status', request('status'))
            )
            ->when(request('due_date'), fn ($q) => $q->whereDate('due_date', request('due_date'))
            )
            ->when(request('search'), fn ($q) => $q->whereFullText(['title', 'description'], request('search'))
            );
    }
}
