<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Word extends Model
{
    protected $table = 'words';
    protected $primarykey = 'id';
    protected $fillable = [
        'word',
        'meaning',
        'pronunciation',
        'description',
        'voice',
        'image',
        'user_id',
    ];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'word_category', 'word_id','category_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_word', 'word_id', 'team_id');
    }
    use HasFactory;
}
