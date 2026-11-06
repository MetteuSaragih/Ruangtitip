<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'phone',
        'campus',
        'major',
        'cohort_year',
        'hometown',
        'notif_prefs',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    /**
     * Casting tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'notif_prefs' => 'array',
        ];
    }

    /**
     * Cek apakah user login via Google.
     */
    public function isGoogleUser(): bool
    {
        return !is_null($this->google_id);
    }

    /**
     * URL foto profil: foto Google berupa URL penuh, unggahan sendiri ada di disk public.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }

        return preg_match('#^https?://#i', $this->avatar)
            ? $this->avatar
            : \Illuminate\Support\Facades\Storage::disk('public')->url($this->avatar);
    }

    /**
     * Accessor untuk inisial nama (Fallback avatar).
     */
    public function getInitialsAttribute(): string
    {
        if (empty($this->name)) {
            return 'U'; // Default jika nama null
        }

        $words = explode(' ', trim($this->name));
        $initials = '';
        
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= mb_strtoupper(mb_substr($word, 0, 1));
        }

        return $initials;
    }
}
