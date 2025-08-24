<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\OnlineApplication\ApprovalSequenceSetupItem;
use App\Traits\Approvable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
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

    protected static function boot()    
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->employee_id)) {
                $model->employee_id = self::generateEmployeeNumber();
            }
        });
    }

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
        ];
    }

    public static function generateEmployeeNumber($type = "employee")
    {
        $datePrefix = now()->format('Ymd');

        $lastRequest = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastRequest) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastRequest->employee_id, -3);
            $number = $lastNumber + 1;
        }

        $prefix = "EMP-";

        if($type != 'employee') $prefix = "HR-";

        return $prefix . $datePrefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function approval_sequence_setup_items(){
        return $this->hasMany(ApprovalSequenceSetupItem::class, 'employee_id', 'employee_id');
    }
}
