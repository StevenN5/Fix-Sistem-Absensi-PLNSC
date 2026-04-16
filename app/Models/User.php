<?php

namespace App\Models; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pin_code',
        'phone_number',
        'address',
        'birth_date',
        'institution',
        'division_id',
        'mentor_id',
        'internship_start_date',
        'internship_end_date',
        'profile_photo_path',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'bank_name',
        'bank_account_number',
    ];

    protected $hidden = [
        'pin_code',
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }

    public function hasRole(string $role): bool
    {
        // Cara aman: cek apakah ada role dengan slug tertentu
        return $this->roles()->where('slug', $role)->exists();
    }

    public function hasAnyRole(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
?>
