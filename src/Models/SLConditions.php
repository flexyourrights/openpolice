<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\SLConditionsVals;

class SLConditions extends Model
{
    protected $table         = 'SL_Conditions';
    protected $primaryKey     = 'CondID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'CondDatabase', 
        'CondTag', 
        'CondDesc', 
        'CondOperator', 
        'CondOperDeet', 
        'CondField', 
        'CondTable', 
        'CondLoop', 
        'CondOpts', 
    ];
    
    public $condVals             = array();
    public $condFldResponses     = array();
    
    public function loadVals()
    {
        $this->condVals = [];
        $chk = SLConditionsVals::where('CondValCondID', $this->CondID)
            ->get();
        if ($chk && sizeof($chk) > 0) {
            foreach ($chk as $v) $this->condVals[] = trim($v->CondValValue);
        }
        
        $this->condFldResponses = $GLOBALS["SL"]->getFldResponsesByID($this->CondField);
        
        if (sizeof($this->condVals) > 0) {
            if (sizeof($this->condFldResponses["vals"]) == 0) {
                foreach ($this->condVals as $j => $val) {
                    $this->condFldResponses["vals"][] = array($val, $val);
                }
            }
            foreach ($this->condVals as $j => $val) {
                $def = $GLOBALS["SL"]->getDefValById(intVal($val));
                $found = false;
                foreach ($this->condFldResponses["vals"] as $k => $valInfo) {
                    if ($valInfo[0] == $val) {
                        $found = true;
                        if (strlen($valInfo[1]) > 40) {
                            if ($def != '') $this->condFldResponses["vals"][$k][1] = $def;
                            else $this->condFldResponses["vals"][$k][1] = $val;
                        }
                        if ($this->condFldResponses["vals"][$k][0] == $this->condFldResponses["vals"][$k][1]
                            && $def != '') {
                            $this->condFldResponses["vals"][$k][1] = $def;
                        }
                    }
                }
                if (!$found && $def != '') $this->condFldResponses["vals"][] = array($val, $def);
            }
        }
        return true;
    }
    
    
    public function tblName()
    {
        return $GLOBALS["SL"]->tbl[$this->CondTable];
    }
    
}
