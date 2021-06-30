<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Pref;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'zip', 'pref_id', 'address1', 'address2', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Pref relation
     *
     * @return relationMethod
     */
    public function pref()
    {
        return $this->belongsTo(Pref::class);
    }

    /**
     * 郵便番号にハイフン追加
     *
     * @param  string  $value
     * @return string
     */
    public function getZipAttribute($value)
    {
        $zip1 = substr($value, 0, 3);
        $zip2 = substr($value, 3);
        if (!empty($zip1) && !empty($zip2)) {
            return "${zip1}-${zip2}";
        }

        return "";
    }

    /**
     * 全ての住所を取得
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $pref_name = $this->pref->name ?? null;
        if (!empty($this->zip) && !empty($pref_name) && !empty($this->address1)) {
            if (!empty($this->address2)) {
                return "{$this->zip} {$pref_name} {$this->address1} {$this->address2}";
            } else {
                return "{$this->zip} {$pref_name} {$this->address1}";
            }
        }
        return "";
    }
}
