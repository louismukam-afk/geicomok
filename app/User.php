<?php

namespace GEICOM;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * GEICOM\User
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property int $role
 * @property int $active
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function canDoAction($actionAgainst) {

        if($this->isAdmin()) {
            return true;
        }
        $a = action::where('action_name', 'like', $actionAgainst.'%')
            ->where('id_user', '=', $this->id)->first();
        if($a != null) {
            return true;
        }
        return false;
    }

    public function isAdmin() {

        $a = action::whereRaw('(action_name = ? OR action_name = ?) AND id_user = ?',[action::ACTION_ALL, action::ACTION_ALMOST_ALL, $this->id])->first();
        if($a != null) {
            return true;
        }

        return false;
    }

    public function isCreator() {
        return $this->hasSpecificAction(action::ACTION_ALL);
    }
    public function isTeacher() {
        return $this->hasSpecificAction(action::ACTION_TEACHER);
    }



    public function hasSpecificAction($actionAgainst) {
        $a = Action::where('action_name', '=', $actionAgainst)
            ->where('id_user', '=', $this->id)->first();
        if($a != null) {
            return true;
        }
        return false;
    }

    public function actions() {
        return  $this->hasMany(action::class,'id_user');

    }
    public function roles()
    {
        return  $this->hasMany(Role::class,'id_user');
    }





    public function boutique()
    {
        return  $this->belongsTo(Boutique::class,'id_boutique');
    }
    public function securite()
    {
        return  $this->hasMany(securite::class,'id_user');
    }
    public function facture()
    {
        return  $this->hasMany(Facture::class,'id_user');
    }
}
