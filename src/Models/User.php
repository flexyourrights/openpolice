<?php 
namespace App\Models;

use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

use App\Models\SLDefinitions;
use App\Models\SLUsersRoles;

use SurvLoop\Controllers\DatabaseLookups;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    
    
    public function printUsername($link = true, $baseurl = '/profile/')
    {
        if ($link) return '<a href="' . $baseurl . $this->id . '">' . $this->name . '</a>';
        return $this->name;
    }
    
    public function printCasualUsername($link = true, $baseurl = '/profile/', $preFix = '')
    {
        $uName = $this->name;
        if (strpos($uName, ' ') !== false) $uName = substr($uName, 0, strpos($uName, ' '));
        if ($link) return '<a href="' . $baseurl . $this->id . '">' . $preFix . $uName . '</a>';
        return $uName;
    }
    
    
    
    /**
     * The information of all possible SurvLoop Roles.
     *
     * @var array
     */
    public $roles = [];
    
    /**
     * The names of SurvLoop Roles held by this user.
     *
     * @var array
     */
    protected $SLRoles = [];
    
    public function loadRoles()
    {
        if (sizeof($this->roles) == 0) {
            $this->roles = SLDefinitions::select('DefID', 'DefSubset')
                ->where('DefDatabase', 1)
                ->where('DefSet', 'User Roles')
                ->orderBy('DefOrder')
                ->get();
            $chk = DB::table('SL_UsersRoles')
                ->join('SL_Definitions', 'SL_UsersRoles.RoleUserRID', '=', 'SL_Definitions.DefID')
                ->where('SL_UsersRoles.RoleUserUID', $this->id)
                ->where('SL_Definitions.DefSet', 'User Roles')
                ->select('SL_Definitions.DefSubset')
                ->get();
            if ($chk && sizeof($chk) > 0) {
                foreach ($chk as $role) {
                    $this->SLRoles[] = $role->DefSubset;
                }
            }
            else $this->SLRoles[] = 'NO-ROLES';
        }
        return true;
    }
    
    public function hasRole($role)
    {
        $this->loadRoles();
        if (strpos($role, '|') === false) return in_array($role, $this->SLRoles);
        $ret = false;
        $roles = explode('|', $role);
        foreach ($roles as $r) {
            if (in_array($r, $this->SLRoles)) $ret = true;
        }
        return $ret;
    }
    
    public function assignRole($role)
    {
        $this->loadRoles();
        $roleDef = SLDefinitions::select('DefID')
            ->where('DefDatabase', 1)
            ->where('DefSet', 'User Roles')
            ->where('DefSubset', $role)
            ->orderBy('DefOrder')
            ->first();
        $chk = SLUsersRoles::select('RoleUserID')
            ->where('RoleUserRID', '=', $roleDef->DefID)
            ->where('RoleUserUID', '=', $this->id)
            ->get();
        if (!$chk || sizeof($chk) == 0) {
            $newRole = new SLUsersRoles;
            $newRole->RoleUserRID = $roleDef->DefID;
            $newRole->RoleUserUID = $this->id;
            $newRole->save();
            $this->SLRoles[] = $role;
        }
        return true;
    }
    
    public function revokeRole($role)
    {
        $this->loadRoles();
        $roleDef = SLDefinitions::select('DefID')
            ->where('DefDatabase', 1)
            ->where('DefSet', 'User Roles')
            ->where('DefSubset', $role)
            ->orderBy('DefOrder')
            ->first();
        $chk = SLUsersRoles::where('RoleUserRID', '=', $roleDef->DefID)
            ->where('RoleUserUID', '=', $this->id)
            ->delete();
        if (sizeof($this->SLRoles) > 0) {
            $roles = $this->SLRoles;
            $this->SLRoles = [];
            foreach ($roles as $r) {
                if ($r != $role) $this->SLRoles[] = $r;
            }
        }
        return true;
    }
    
    public function highestPermission()
    {
        $this->loadRoles();
        foreach ($this->roles as $role) {
            if ($this->hasRole($role->DefSubset)) return $role->DefSubset;                                             
        }
        return '';
    }
    
    public function listRoles()
    {
        $this->loadRoles();
        $retVal = '';
        foreach ($this->roles as $role) { 
            if ($this->hasRole($role->DefSubset)) {
                $retVal .= ', ' . ucfirst($role->DefSubset);
            }
        }
        if ($retVal != '') $retVal = substr($retVal, 2);
        return $retVal;
    }
    
    public function hasVerifiedEmail()
    {
        $chk = SLUsersRoles::select('RoleUserID')
            ->where('RoleUserRID', '=', -37)
            ->where('RoleUserUID', '=', $this->id)
            ->first();
        return ($chk && isset($chk->RoleUserID));
    }
    
}
