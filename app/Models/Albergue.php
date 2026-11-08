<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Albergue extends Model
{
    protected $fillable = ['user_id', 'nombre', 'distrito', 'descripcion', 'validado'];

    protected $casts = ['validado' => 'boolean'];

    /** Usuario responsable del albergue. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Mascotas publicadas por el albergue. */
    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class);
    }
}