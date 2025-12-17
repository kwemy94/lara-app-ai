<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AiMessage::class);
    }

    public function getFormattedMessagesForOpenAI(): array
    {
        return $this->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($message) => [
                'role' => $message->role,
                'content' => $message->content,
            ])
            ->toArray();
    }
}
