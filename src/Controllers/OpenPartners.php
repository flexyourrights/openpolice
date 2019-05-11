<?php
namespace OpenPolice\Controllers;

use DB;
use Illuminate\Http\Request;
use Storage\App\Models\OPPartners;
use Storage\App\Models\OPOversight;
use Storage\App\Models\OPLinksComplaintDept;
use Storage\App\Models\OPPartnerCaseTypes;
use Storage\App\Models\OPPartnerCapac;
use OpenPolice\Controllers\VolunteerLeaderboard;
use OpenPolice\Controllers\OpenVolunteers;

class OpenPartners extends OpenVolunteers
{
    protected function printPartnersOverview()
    {
        $this->loadPartnerTypes();
        foreach ($this->v["prtnTypes"] as $i => $p) {
            $this->v["prtnTypes"][$i]["tot"] = OPPartners::where('PartType', $p["defID"])
                ->count();
        }
        return view('vendor.openpolice.nodes.1939-manage-partners-overview', $this->v)->render();
    }
    
    protected function printPartnerCapabilitiesOverview()
    {
        $capac = [];
        $defs = $GLOBALS["SL"]->def->getSet('Organization Capabilities');
        foreach ($defs as $i => $def) {
            $capac[] = [
                "def" => $def->DefValue,
                "tot" => OPPartnerCapac::where('PrtCapCapacity', $def->DefID)
                    ->count()
                ];
        }
        return view('vendor.openpolice.nodes.2169-partner-overview-capabilities', [ "capac" => $capac ])->render();
    }
    
    protected function printManageAttorneys($type = 'Attorney')
    {
        $ret = '';
        $this->loadPartnerTypes();
        $defAtt = $GLOBALS["SL"]->def->getID('Partner Types', $type);
        if ($GLOBALS["SL"]->REQ->has('add')) {
            $newAtt = new OPPartners;
            $newAtt->PartType = (($GLOBALS["SL"]->REQ->has('type')) 
                ? $this->loadPrtnDefID($GLOBALS["SL"]->REQ->get('type')) : null);
            $newAtt->save();
            $this->redir('/dashboard/start-' . $newAtt->PartID . '/partner-profile', true);
        }
        foreach ($this->v["prtnTypes"] as $p) {
            if ($p["defID"] == $defAtt) {
                $this->v["partners"] = $this->getPartnersOfType($p["defID"]);
                $this->v["prtnType"] = $p;
                return view('vendor.openpolice.nodes.2166-manage-attorneys', $this->v)->render();
            }
        }
        return '';
    }
    
    protected function getPartnersOfType($partTypeDef = 584, $statusIn = [1])
    {
        return DB::table('OP_Partners')
            ->join('OP_PersonContact', 'OP_PersonContact.PrsnID', '=', 'OP_Partners.PartPersonID')
            ->leftJoin('users', 'users.id', '=', 'OP_Partners.PartUserID')
            ->where('OP_Partners.PartType', $partTypeDef)
            ->whereIn('OP_Partners.PartStatus', $statusIn)
            ->select('OP_Partners.*', 'users.name', 'users.email', 'OP_PersonContact.PrsnNickname', 
                'OP_PersonContact.PrsnNameFirst', 'OP_PersonContact.PrsnNameLast',
                'OP_PersonContact.PrsnAddressCity', 'OP_PersonContact.PrsnAddressState')
            ->orderBy('OP_PersonContact.PrsnNickname', 'asc')
            ->get();
    }
    
    protected function loadPartnerCapabLookups($inEnglish = false)
    {
        $lookup = [];
        $links = OPPartnerCapac::get();
        if ($links->isNotEmpty()) {
            foreach ($links as $lnk) {
                if (isset($lnk->PrtCapPartID) && intVal($lnk->PrtCapPartID) > 0 && isset($lnk->PrtCapCapacity)
                    && intVal($lnk->PrtCapCapacity) > 0) {
                    if (!isset($lookup[$lnk->PrtCapPartID])) {
                        $lookup[$lnk->PrtCapPartID] = [];
                    }
                    if ($inEnglish) {
                        $lookup[$lnk->PrtCapPartID][] 
                            = $GLOBALS["SL"]->def->getVal('Organization Capabilities', $lnk->PrtCapCapacity);
                    } else {
                        $lookup[$lnk->PrtCapPartID][] = $lnk->PrtCapCapacity;
                    }
                }
            }
        }
        return $lookup;
    }
    
    protected function initPartnerCaseTypes($nID)
    {
        if (!isset($this->sessData->dataSets["PartnerCaseTypes"])
            || sizeof($this->sessData->dataSets["PartnerCaseTypes"]) == 0) {
            $this->sessData->dataSets["PartnerCaseTypes"] = [];
            for ($i = 0; $i < 3; $i++) {
                $this->sessData->dataSets["PartnerCaseTypes"][$i] = new OPPartnerCaseTypes;
                $this->sessData->dataSets["PartnerCaseTypes"][$i]->PrtCasPartnerID = $this->coreID;
                $this->sessData->dataSets["PartnerCaseTypes"][$i]->save();
            }
        }
        if ($GLOBALS["SL"]->REQ->has('nv2074') && intVal($GLOBALS["SL"]->REQ->get('nv2074')) > 0
            && isset($this->sessData->dataSets["Partners"])) {
            $this->sessData->dataSets["Partners"][0]->PartType = intVal($GLOBALS["SL"]->REQ->get('nv2074'));
        }
        return '';
    }
    
    protected function printPartnersOverviewPublic($nID = -3)
    {
        return view('vendor.openpolice.nodes.2179-list-organizations', [
            "orgs"  => $this->getPartnersOfType($GLOBALS["SL"]->def->getID('Partner Types', 'Organization')),
            "capab" => $this->loadPartnerCapabLookups(true)
            ])->render();
    }

    protected function loadPartnerPage(Request $request, $prtnSlug = '', $type = 'attorney', $tree = 56)
    {
        $partID = -3;
        $partRow = OPPartners::where('PartSlug', $prtnSlug)
            ->first();
        if ($partRow && isset($partRow->PartID)) {
            $partID = $partRow->PartID;
            $request->atr = $partRow->PartID;
        }
        $this->loadPageVariation($request, 1, $tree, '/' . $type . '/' . $prtnSlug);
        if ($partID > 0) {
            $this->coreID = $partRow->PartID;
            $this->loadAllSessData($GLOBALS["SL"]->coreTbl, $this->coreID);
        }
        return $this->index($request);
    }
    
    protected function publicPartnerHeader($nID = -3)
    {
        $coreID = (($this->coreID > 0) ? $this->coreID : 1);
        $this->loadSessionData('Partners', $coreID);
        if (!isset($this->sessData->dataSets["Partners"])) {
            return '';
        }
        return view('vendor.openpolice.nodes.1961-public-attorney-header', [
            "nID" => $nID,
            "dat" => $this->sessData->dataSets,
            "slg" => (($this->sessData->dataSets['Partners'][0]->PartType 
                == $GLOBALS['SL']->def->getID('Partner Types', 'Attorney')) ? 'attorney' : 'org')
            ])->render();
    }
    
    protected function partnerShareStory(Request $request, $prtnSlug = '', $type = 'attorney', $tree = 62)
    {
        $this->loadPageVariation($request, 1, $tree, '/preparing-complaint-for-' . $type . '/' . $prtnSlug);
        $partRow = OPPartners::where('PartSlug', $prtnSlug)
            ->first();
        if ($partRow && isset($partRow->PartID)) {
            session()->put('opcPartID', $partRow->PartID);
        }
        return $this->index($request);
    }
    
    protected function printPreparePartnerHeader($nID = -3)
    {
        $coreID = (($this->coreID > 0) ? $this->coreID : -3);
        if (session()->has('opcPartID')) {
            $coreID = session()->get('opcPartID');
        }
        if ($GLOBALS["SL"]->REQ->has('atr') && intVal($GLOBALS["SL"]->REQ->get('atr'))) {
            $coreID = intVal($GLOBALS["SL"]->REQ->get('atr'));
        }
        // link partner with [new] complaint record
        
        $this->loadSessionData('Partners', $coreID);
        if (!isset($this->sessData->dataSets["Partners"]) 
            || !isset($this->sessData->dataSets["PersonContact"][0]->PrsnNickname) 
            || trim($this->sessData->dataSets["PersonContact"][0]->PrsnNickname) == '') {
            return '';
        }
        return view('vendor.openpolice.nodes.2069-prepare-complaint-org', [
            "nID" => $nID,
            "dat" => $this->sessData->dataSets
            ])->render();
    }
    
    protected function printAttorneyReferrals($nID = -3)
    {
        
        return view('vendor.openpolice.nodes.1896-attorney-referral-listings', [
            "nID" => $nID
            ])->render();
    }
    
    public function attorneyPage(Request $request, $prtnSlug = '')
    {
        return $this->loadPartnerPage($request, $prtnSlug, 'attorney', 56);
    }
    
    public function shareStoryAttorney(Request $request, $prtnSlug = '')
    {
        return $this->partnerShareStory($request, $prtnSlug, 'attorney', 62);
    }
    
    public function orgPage(Request $request, $prtnSlug = '')
    {
        return $this->loadPartnerPage($request, $prtnSlug, 'org', 65);
    }
    
    public function shareStoryOrg(Request $request, $prtnSlug = '')
    {
        return $this->partnerShareStory($request, $prtnSlug, 'org', 66);
    }
    
    protected function publicPartnerPage($nID = -3)
    {
        if (!isset($this->sessData->dataSets["Partners"])) {
            return '';
        }
        return view('vendor.openpolice.nodes.1898-public-attorney-page', [
            "nID"  => $nID,
            "dat"  => $this->sessData->dataSets,
            "type" => $this->sessData->dataSets["Partners"][0]->PartType
            ])->render();
    }
    
    protected function volunStars()
    {
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        $this->v["yourStats"] = [];
        if ($this->v["leaderboard"]->UserInfoStars && sizeof($this->v["leaderboard"]->UserInfoStars) > 0) {
            foreach ($this->v["leaderboard"]->UserInfoStars as $i => $volunUser) {
                if ($volunUser->UserInfoUserID == $this->v["uID"]) {
                    $this->v["yourStats"] = $volunUser;
                }
            }
        }
        return view('vendor.openpolice.volun.stars', $this->v)->render();
    }
    
    protected function setUserOversightFilt()
    {
        if (!isset($this->v["fltIDs"])) {
            $this->v["fltIDs"] = [];
        }
        $this->v["fltDept"] = -3;
        if ($this->v["user"]->hasRole('partner') && $this->v["user"]->hasRole('oversight')) {
            $this->v["fltIDs"][0] = [];
            $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)->first();
            if ($overRow && isset($overRow->OverDeptID)) {
                $this->v["fltDept"] = $overRow->OverDeptID;
                $lnkChk = OPLinksComplaintDept::select('LnkComDeptComplaintID')
                    ->where('LnkComDeptDeptID', $overRow->OverDeptID)
                    ->get();
                if ($lnkChk->isNotEmpty()) {
                    foreach ($lnkChk as $lnk) {
                        $this->v["fltIDs"][0][] = $lnk->LnkComDeptComplaintID;
                    }
                }
            }
        }
        return true;
    }
    
    public function joinBetaLink(Request $request, $campaign = '')
    {
        header("Location: /start/beta-test-signup?from=" . $campaign);
        exit;
    }
    
}