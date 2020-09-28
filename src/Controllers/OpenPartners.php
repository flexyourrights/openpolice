<?php
/**
  * OpenPartners is mid-level class with functions for managing various
  * organizational partners, include those for their custom pages.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\OPPartners;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPPartnerCaseTypes;
use App\Models\OPPartnerCapac;
use FlexYourRights\OpenPolice\Controllers\VolunteerLeaderboard;
use FlexYourRights\OpenPolice\Controllers\OpenVolunteers;

class OpenPartners extends OpenVolunteers
{
    /**
     * Print the top-level overview dashboard
     * for managing all types of partners.
     *
     * @return string
     */
    protected function printPartnersOverview()
    {
        $this->loadPartnerTypes();
        foreach ($this->v["prtnTypes"] as $i => $p) {
            $this->v["prtnTypes"][$i]["tot"] = OPPartners::where('part_type', $p["defID"])->count();
        }
        return view(
            'vendor.openpolice.nodes.1939-manage-partners-overview', 
            $this->v
        )->render();
    }
    
    /**
     * Print out the capabilities listing for this partner.
     *
     * @return string
     */
    protected function printPartnerCapabilitiesOverview()
    {
        $capac = [];
        $defs = $GLOBALS["SL"]->def->getSet('Organization Capabilities');
        foreach ($defs as $i => $def) {
            $capac[] = [
                "def" => $def->def_value,
                "tot" => OPPartnerCapac::where('prt_cap_capacity', $def->def_id)->count()
            ];
        }
        return view(
            'vendor.openpolice.nodes.2169-partner-overview-capabilities', 
            [ "capac" => $capac ]
        )->render();
    }
    
    /**
     * Print out a top-level listing of partners to be managed from
     * a page which only manages them.
     *
     * @param  string $type
     * @return string
     */
    protected function printManagePartners($nID = -3, $type = 'Attorney')
    {
        $ret = '';
        $this->loadPartnerTypes();
        $defAtt = $GLOBALS["SL"]->def->getID('Partner Types', $type);
        if ($GLOBALS["SL"]->REQ->has('add')) {
            $newAtt = new OPPartners;
            $newAtt->part_type = (($GLOBALS["SL"]->REQ->has('type')) 
                ? $this->loadPrtnDefID($GLOBALS["SL"]->REQ->get('type')) 
                : null);
            $newAtt->save();
            $redir = '/dashboard/start-' . $newAtt->part_id . '/partner-profile';
            $this->redir($redir, true);
        }
        $statusIn = [1];
        if ($nID == 2171) {
            $statusIn = [0, 1];
        }
        foreach ($this->v["prtnTypes"] as $p) {
            if ($p["defID"] == $defAtt) {
                $this->v["partners"] = $this->getPartnersOfType($p["defID"], $statusIn);
                $this->v["prtnType"] = $p;
                return view(
                    'vendor.openpolice.nodes.2166-manage-partners', 
                    $this->v
                )->render();
            }
        }
        return '';
    }
    
    /**
     * Retrieve the Partner records belonging to a certain Partner Type.
     *
     * @param  int $partTypeDef
     * @param  array $statusIn
     * @return DB
     */
    protected function getPartnersOfType($partTypeDef = 584, $statusIn = [1])
    {
        return DB::table('op_partners')
            ->join('op_person_contact', 'op_person_contact.prsn_id', '=', 'op_partners.part_person_id')
            ->leftJoin('users', 'users.id', '=', 'op_partners.part_user_id')
            ->where('op_partners.part_type', $partTypeDef)
            ->whereIn('op_partners.part_status', $statusIn)
            ->select(
                'op_partners.*', 
                'users.name', 
                'users.email', 
                'op_person_contact.prsn_nickname', 
                'op_person_contact.prsn_name_first', 
                'op_person_contact.prsn_name_last',
                'op_person_contact.prsn_address_city', 
                'op_person_contact.prsn_address_state',
                'op_person_contact.prsn_email'
            )
            ->orderBy('op_person_contact.prsn_nickname', 'asc')
            ->get();
    }
    
    /**
     * Retrieve the Partner capabilities for faster lookup later.
     *
     * @param  boolean $inEnglish
     * @return array
     */
    protected function loadPartnerCapabLookups($inEnglish = false)
    {
        $lookup = [];
        $links = OPPartnerCapac::get();
        if ($links->isNotEmpty()) {
            foreach ($links as $lnk) {
                if (isset($lnk->prt_cap_part_id) 
                    && intVal($lnk->prt_cap_part_id) > 0 
                    && isset($lnk->prt_cap_capacity)
                    && intVal($lnk->prt_cap_capacity) > 0) {
                    if (!isset($lookup[$lnk->prt_cap_part_id])) {
                        $lookup[$lnk->prt_cap_part_id] = [];
                    }
                    if ($inEnglish) {
                        $lookup[$lnk->prt_cap_part_id][] = $GLOBALS["SL"]->def->getVal(
                            'Organization Capabilities', 
                            $lnk->prt_cap_capacity
                        );
                    } else {
                        $lookup[$lnk->prt_cap_part_id][] = $lnk->prt_cap_capacity;
                    }
                }
            }
        }
        return $lookup;
    }
    
    /**
     * Load the different types of cases the current partner has defined.
     *
     * @param  int $nID
     * @return string
     */
    protected function initPartnerCaseTypes($nID)
    {
        if (!isset($this->sessData->dataSets["partner_case_types"])
            || sizeof($this->sessData->dataSets["partner_case_types"]) == 0) {
            $this->sessData->dataSets["partner_case_types"] = [];
            for ($i = 0; $i < 3; $i++) {
                $case = new OPPartnerCaseTypes;
                $case->prt_cas_partner_id = $this->coreID;
                $case->save();
                $this->sessData->dataSets["partner_case_types"][$i] = $case;
            }
        }
        if ($GLOBALS["SL"]->REQ->has('nv2074') 
            && intVal($GLOBALS["SL"]->REQ->get('nv2074')) > 0
            && isset($this->sessData->dataSets["partners"])) {
            $this->sessData->dataSets["partners"][0]->part_type 
                = intVal($GLOBALS["SL"]->REQ->get('nv2074'));
        }
        return '';
    }
    
    /**
     * Print a staff listing to manage partner organizations.
     *
     * @param  int $nID
     * @return string
     */
    protected function printPartnersOverviewPublic($nID = -3)
    {
        $orgDef = $GLOBALS["SL"]->def->getID('Partner Types', 'Organization');
        return view(
            'vendor.openpolice.nodes.2179-list-organizations', 
            [
                "orgs"  => $this->getPartnersOfType($orgDef),
                "capab" => $this->loadPartnerCapabLookups(true)
            ]
        )->render();
    }

    /**
     * Load and print a partner's page/profile.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $prtnSlug
     * @param  string $type
     * @param  int $tree
     * @return string
     */
    protected function loadPartnerPage(Request $request, $prtnSlug = '', $type = 'attorney', $tree = 56)
    {
        $partID = -3;
        $partRow = OPPartners::where('part_slug', $prtnSlug)
            ->first();
        if ($partRow && isset($partRow->part_id)) {
            $partID = $partRow->part_id;
            $request->atr = $partRow->part_id;
        }
        $this->loadPageVariation($request, 1, $tree, '/' . $type . '/' . $prtnSlug);
        if ($partID > 0) {
            $this->coreID = $partRow->part_id;
            $this->loadAllSessData($GLOBALS["SL"]->coreTbl, $this->coreID);
        }
        return $this->index($request);
    }
    
    /**
     * Print a partner's page/profile header or main title.
     *
     * @param  int $nID
     * @return string
     */
    protected function publicPartnerHeader($nID = -3)
    {
        $coreID = (($this->coreID > 0) ? $this->coreID : 1);
        $this->loadSessionData('partners', $coreID);
        if (!isset($this->sessData->dataSets["partners"])) {
            return '';
        }
        $attDef = $GLOBALS['SL']->def->getID('Partner Types', 'Attorney');
        $slg = (($this->sessData->dataSets["partners"][0]->part_type == $attDef) ? 'attorney' : 'org');
        return view(
            'vendor.openpolice.nodes.1961-public-partner-header', 
            [
                "nID" => $nID,
                "dat" => $this->sessData->dataSets,
                "slg" => $slg
            ]
        )->render();
    }

    /**
     * Print a partner's page/profile header or main title.
     *
     * @param  Illuminate\Http\Request $request
     * @return int
     */
    protected function getPartnerCoreID(Request $request)
    {
        $coreID = (($this->coreID > 0) ? $this->coreID : -3);
        if (session()->has('opcPartID')) {
            $coreID = session()->get('opcPartID');
        }
        if ($request->has('atr') && intVal($request->get('atr'))) {
            $coreID = intVal($request->get('atr'));
        }
        return $coreID;
    }
    
    /**
     * Print a partner's affiliate link for the start of the survey.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $prtnSlug
     * @param  string $type
     * @param  int $tree
     * @return string
     */
    protected function partnerShareStory(Request $request, $prtnSlug = '', $type = 'attorney', $tree = 62)
    {
        $pageUrl = '/preparing-complaint-for-' . $type . '/' . $prtnSlug;
        $this->loadPageVariation($request, 1, $tree, $pageUrl);
        $partRow = OPPartners::where('part_slug', $prtnSlug)
            ->first();
        if ($partRow && isset($partRow->part_id)) {
            session()->put('opcPartID', $partRow->part_id);
            $request->cid = $partRow->part_id;
        }
        $coreID = $this->getPartnerCoreID($GLOBALS["SL"]->REQ);
        if ($coreID > 0) {
            $this->loadSessionData('partners', $coreID);
        }
        return $this->index($request);
    }
    
    /**
     * Print a partner's 'affiliate' page for the start of the survey.
     *
     * @param  int $nID
     * @return string
     */
    protected function printPreparePartnerHeader($nID = -3)
    {
        $coreID = $this->getPartnerCoreID($GLOBALS["SL"]->REQ);
        // link partner with [new] complaint record
        
        $this->loadSessionData('partners', $coreID);
        $psrnCont = $this->sessData->dataSets["person_contact"][0];
        if (!isset($this->sessData->dataSets["partners"]) 
            || !isset($psrnCont->prsn_nickname) 
            || trim($psrnCont->prsn_nickname) == '') {
            return '';
        }
        return view(
            'vendor.openpolice.nodes.2069-prepare-complaint-org', 
            [
                "nID" => $nID,
                "dat" => $this->sessData->dataSets
            ]
        )->render();
    }
    
    /**
     * Print the specific referral listings for attorneys.
     *
     * @param  int $nID
     * @return string
     */
    protected function printAttorneyReferrals($nID = -3)
    {
        return view(
            'vendor.openpolice.nodes.1896-attorney-referral-listings', 
            [ "nID" => $nID ]
        )->render();
    }
    
    /**
     * Load and print a attorney/law firm partner's page/profile.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $prtnSlug
     * @return string
     */
    public function attorneyPage(Request $request, $prtnSlug = '')
    {
        return $this->loadPartnerPage($request, $prtnSlug, 'attorney', 56);
    }
    
    /**
     * Print an attorney partner's affiliate page to start of the survey.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $prtnSlug
     * @return string
     */
    public function shareStoryAttorney(Request $request, $prtnSlug = '')
    {
        return $this->partnerShareStory($request, $prtnSlug, 'attorney', 62);
    }
    
    /**
     * Print an organizational partner's profile page.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $prtnSlug
     * @return string
     */
    public function orgPage(Request $request, $prtnSlug = '')
    {
        return $this->loadPartnerPage($request, $prtnSlug, 'org', 65);
    }
    
    /**
     * Print an organizational partner's affiliate link to start of the survey.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $prtnSlug
     * @return string
     */
    public function shareStoryOrg(Request $request, $prtnSlug = '')
    {
        return $this->partnerShareStory($request, $prtnSlug, 'org', 66);
    }
    
    /**
     * Print an organizational partner's public profile page.
     *
     * @param  int $nID
     * @return string
     */
    protected function publicPartnerPage($nID = -3)
    {
        if (!isset($this->sessData->dataSets["partners"])) {
            return '';
        }
        return view(
            'vendor.openpolice.nodes.1898-public-partner-page', 
            [
                "nID"  => $nID,
                "dat"  => $this->sessData->dataSets,
                "type" => $this->sessData->dataSets["partners"][0]->part_type
            ]
        )->render();
    }
    
    /**
     * Print an organizational partner's public profile page,
     * which only collaborates with in-person clinics.
     *
     * @param  int $nID
     * @return string
     */
    protected function publicPartnerPageClinicOnly($nID = -3)
    {
        return view(
            'vendor.openpolice.nodes.2677-partner-clinic-only', 
            [
                "dat"  => $this->sessData->dataSets,
                "type" => $this->sessData->dataSets["partners"][0]->part_type
            ]
        )->render();
    }
    
    /**
     * Print the current volunteer leaderboard, in stars.
     *
     * @return string
     */
    protected function volunStars()
    {
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        $this->v["yourStats"] = [];
        if ($this->v["leaderboard"]->UserInfoStars 
            && sizeof($this->v["leaderboard"]->UserInfoStars) > 0) {
            foreach ($this->v["leaderboard"]->UserInfoStars as $i => $volunUser) {
                if ($volunUser->user_info_user_id == $this->v["uID"]) {
                    $this->v["yourStats"] = $volunUser;
                }
            }
        }
        return view('vendor.openpolice.volun.stars', $this->v)->render();
    }
    
    /**
     * Looks up an array of complaint ID#s associated with an oversight agency.
     *
     * @return boolean
     */
    protected function setUserOversightFilt()
    {
        if (!isset($this->v["fltIDs"])) {
            $this->v["fltIDs"] = [];
        }
        $this->v["fltDept"] = -3;
        if ($this->v["user"]->hasRole('partner') && $this->v["user"]->hasRole('oversight')) {
            $this->v["fltIDs"][0] = [];
            $overRow = OPOversight::where('over_email', $this->v["user"]->email)
                ->first();
            if ($overRow && isset($overRow->over_dept_id)) {
                $this->v["fltDept"] = $overRow->over_dept_id;
                $lnkChk = OPLinksComplaintDept::select('lnk_com_dept_complaint_id')
                    ->where('lnk_com_dept_dept_id', $overRow->over_dept_id)
                    ->get();
                if ($lnkChk->isNotEmpty()) {
                    foreach ($lnkChk as $lnk) {
                        $this->v["fltIDs"][0][] = $lnk->lnk_com_dept_complaint_id;
                    }
                }
            }
        }
        return true;
    }
    
    /**
     * Redirect the beta tester campaign link to the signup survey,
     * with the paramater to track the signup referral.
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $campaign
     * @return boolean
     */
    public function joinBetaLink(Request $request, $campaign = '')
    {
        header("Location: /start/beta-test-signup?from=" . $campaign);
        exit;
    }
    
}