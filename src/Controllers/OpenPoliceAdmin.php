<?php
/**
  * OpenPoliceAdmin extends Survloop's AdminController for
  * pages and internal systems requiring a user login — or more.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.1
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SLDefinitions;
use App\Models\SLNode;
use App\Models\OPComplaints;
use App\Models\OPAllegations;
use App\Models\OPOfficers;
use App\Models\OPPersonContact;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPCustomers;
use App\Models\OPzEditDepartments;
use App\Models\OPzEditOversight;
use App\Models\OPzVolunStatDays;
use App\Models\OPzVolunUserInfo;
use App\Models\SLEmails;
use App\Models\SLEmailed;
use App\Models\OPzComplaintEmailed;
use App\Models\OPzComplaintReviews;
use FlexYourRights\OpenPolice\Controllers\OpenPolice;
use RockHopSoft\Survloop\Controllers\Admin\AdminController;

class OpenPoliceAdmin extends AdminController
{
    private $currUser = null;
    private $currPage = '';
    
    function __construct($currUser = null, $currPage = '')
    {
        $this->currUser = $currUser;
        $this->currPage = $currPage;
    }
    
    function clearIncompletes()
    {
        /*
        if (!isset($_SESSION["clearIncompletes"])) {
            $qmen = $delComs = [];
            $qman = "SELECT * FROM `complaints` WHERE `com_created` < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("n"), date("j")-2, date("Y"))) . "' AND (`com_submission_progress` < '1' OR `com_summary` IS NULL)";
            $chk = mysqli_query($GLOBALS["SL"], $qman); // echo $qman . '<br />';
            if ($chk && mysqli_num_rows($chk) > 0) { 
                while ($row = mysqli_fetch_array($chk)) { $delComs[] = $row["ComID"]; }
                $qmen[] = "DELETE FROM `Civilians` WHERE `civ_complaint_id` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Scenes` WHERE `scn_complaint_id` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Incidents` WHERE `inc_complaint_id` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `complaints` WHERE `com_id` IN ('" . implode("', '", $delComs) . "')";
            }
            $qmen[] = "DELETE FROM `AuthUser` WHERE `UserCreated` < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("n"), date("j")-2, date("Y"))) . "' AND `UserName` IS NULL AND `UserPassword` IS NULL AND `UserID` NOT IN (SELECT `civ_user_id` FROM `Civilians`)";
            foreach ($qmen as $qman) {
                $chk = mysqli_query($GLOBALS["SL"], $qman); // echo $qman . '<br />';
            }
            $_SESSION["clearIncompletes"] = true;
        }
        */
    }
    
    
    
    
    public function listOfficers(Request $request)
    {
        $this->admControlInit($request, '/dashboard/officers');
        $this->v["officers"] = OPOfficers::get();
        return view(
            'vendor.openpolice.admin.lists.officers', 
            $this->v
        )->render();
    }
    
    public function listDepts(Request $request)
    {
        $this->admControlInit($request, '/dashboard/depts');
        $this->v["deptComplaints"] = $this->v["deptAllegs"] = $deptList = [];
        $comDeptLnks = OPLinksComplaintDept::all();
        if ($comDeptLnks->isNotEmpty()) {
            foreach ($comDeptLnks as $lnk) {
                if (!in_array($lnk->lnk_com_dept_dept_id, $deptList)) {
                    $deptList[] = $lnk->lnk_com_dept_dept_id;
                }
                if (!isset($this->v["deptComplaints"][$lnk->lnk_com_dept_dept_id])) {
                    $this->v["deptComplaints"][$lnk->lnk_com_dept_dept_id] = [];
                    $this->v["deptAllegs"][$lnk->lnk_com_dept_dept_id] = [];
                }
                $this->v["deptComplaints"][$lnk->lnk_com_dept_dept_id][] = $lnk->lnk_com_dept_complaint_id;
            }
        }
        $this->v["departments"] = OPDepartments::whereIn('dept_id', $deptList)
            ->get();
        return view('vendor.openpolice.admin.lists.depts', $this->v)->render();
    }
    
    public function quickAssign(Request $request)
    {
        $this->admControlInit($request);
        if ($request->over_id > 0 && $request->dept_id > 0) {
            $over = OPOversight::find($request->over_id);
            $over->over_dept_id = $request->dept_id;
            $over->save();
        }
        return $this->redir('/dashboard/overs#o' . $request->over_id);
    }
    
    public function listLegal(Request $request)
    {
        $this->admControlInit($request, '/dashboard/legal');
        $this->v["attorneys"] = OPCustomers::where('cust_type', 'Attorney')
            ->get();
        return view('vendor.openpolice.admin.lists.legal', $this->v)->render();
    }
    
    public function listAcademic(Request $request)
    {
        $this->admControlInit($request, '/dashboard/academic');
        $this->v["academic"] = OPCustomers::where('cust_type', 'Academic')
            ->get();
        return view('vendor.openpolice.admin.lists.academic', $this->v)->render();
    }
    
    public function listMedia(Request $request)
    {
        $this->admControlInit($request, '/dashboard/media');
        $this->v["journalists"] = OPCustomers::where('cust_type', 'Journalist')
            ->get();
        return view('vendor.openpolice.admin.lists.media', $this->v)->render();
    }
    
}
