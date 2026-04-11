<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function pengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class);
    }

    public function assignedPengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'assigned_admin_id');
    }

    public function approvedPengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'approved_by');
    }

    public function rejectedPengajuanKredits()
    {
        return $this->hasMany(PengajuanKredit::class, 'rejected_by');
    }

    public function createdCredits()
    {
        return $this->hasMany(Kredit::class, 'created_by');
    }

    public function verifiedInstallments()
    {
        return $this->hasMany(Angsuran::class, 'verified_by');
    }

    public function isRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }
}
