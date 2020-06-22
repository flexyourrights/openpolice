<?php
/**
  * OpenPolicePCIF is a class to manually create the exports for the
  * Police Complaint Interchange Format (PCIF), so that entire 
  * parallel data strutures aren't managed via SurvLoop.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.2.17
  */
namespace OpenPolice\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use SurvLoop\Controllers\Globals\Globals;
use SurvLoop\Controllers\Tree\TreeCustomAPI;

class OpenPolicePCIF extends OpenListing
{

	public function printPcifSchemaType(Request $request, $type = 'xml')
	{
		$GLOBALS["SL"] = new Globals($request, 1, 1, 1);
        $pageUrl = '/api/complaints-pcif-schema-' . $type;
        $ret = $GLOBALS["SL"]->chkCache($pageUrl, 'api-cust', 1);
        if (trim($ret) == '' || $request->has('refresh')) {
		    $this->loadPcifSchema($type);
    		$filename = 'complaints-pcif-schema_' . date("Ymd") . '.' . $type;
    		$ret = $this->v["pcif"]->returnSchema($type, $filename, $pageUrl);
            $GLOBALS["SL"]->putCache($pageUrl, $ret, 'api-cust', 1);
        }
        if ($type == 'xml') {
            return Response::make($ret, '200')
                ->header('Content-Type', 'text/xml');
        }
        // else $type == 'eng'
        return view('vendor.survloop.master', [ "content" => $ret ])->render();
	}

	public function printPcifSchema(Request $request)
	{
		return $this->printPcifSchemaType($request, 'eng');
	}

	public function printPcifSchemaXml(Request $request)
	{
		return $this->printPcifSchemaType($request, 'xml');
	}

	public function printPcifExample(Request $request, $type = 'xml')
	{
		$GLOBALS["SL"] = new Globals($request, 1, 1, 1);
		$this->loadPcifSchema($type);

	}

	public function printPcifAll(Request $request, $type = 'xml')
	{
        $this->loadPageVariation($request, 1, 1, '/api/complaints-pcif-xml');
        $GLOBALS["SL"]->pageView = 'public';
        $ret = $GLOBALS["SL"]->chkCache('/api/complaints-pcif-xml', 'api-cust', 1);
        if (trim($ret) == '' || $request->has('refresh')) {
    		$limit = $GLOBALS["SL"]->getLimit();
    		$this->loadPcifSchema($type, true);
            $ret = view(
                'vendor.survloop.admin.tree.xml-header-custom',
                [ "apiDesc" => $this->v["pcif"]->apiDesc ]
            )->render();
            $ret .= '<' . $this->v["pcif"]->corePlural . ' xmlns="' 
                . $this->v["pcif"]->apiNamespace . '" xmlns:xsi="' 
                . $this->v["pcif"]->apiNamespace . '" xsi:schemaLocation="' 
                . $this->v["pcif"]->apiSchema . '" xsi:noNamespaceSchemaLocation="' 
                . $this->v["pcif"]->apiSchema . '">';
        	$allIDs = $this->getAllPublicCoreIDs('complaints');
            if (sizeof($allIDs) > 0) {
                foreach ($allIDs as $i => $coreID) {
                    if ($i < $limit) {
    		            $this->coreID = $coreID;
    		            $this->loadAllSessData('complaints', $coreID);
                      	if (isset($this->sessData->dataSets["complaints"]) 
                            && sizeof($this->sessData->dataSets["complaints"]) > 0) {
                        	$pcifID = 'OP' . $this->sessData->dataSets["complaints"][0]->com_public_id;
    			            $ret .= $this->printRecordCustomAPI('pcif', $type, $pcifID);
                        }
                    }
                }
            }
            $ret .= '</' . $this->v["pcif"]->corePlural . '>';
            $GLOBALS["SL"]->putCache('/api/complaints-pcif-xml', $ret, 'api-cust', 1);
        }
        return Response::make($ret, '200')->header('Content-Type', 'text/xml');
	}

	public function loadPcifSchema($type = 'xml', $opOnly = false)
	{
		$apiName = 'Police Complaint Interchange Format (PCIF)';
		$desc = view('vendor.openpolice.api.pcif-schema-header', [
            "type" => $type
        ])->render();
		$this->v["pcif"] = new TreeCustomAPI($apiName, 'complaints', 'complaint', $desc);
		$this->v["pcif"]->setSchema('https://openpolice.org/api/complaints-pcif-schema-xml');

		$this->v["pcif"]->addTable('complaints', 'complaint', 'complaints');
		$this->v["pcif"]->addFld(
			'complaints', 
			'pcif_id', 
			'PCIF Complaint ID#', 
			'Indicates the unique PCIF Complaint ID# for this police conduct record. '
				. 'Records sourced from Raheem.ai begin with "RA" '
				. 'and OpenPolice.org records begin with "OP". e.g. OP81, RA2397'
		);
		$this->v["pcif"]->addFld(
			'complaints', 
			'url', 
			'Published Complaint URL', 
			'Indicates the URL where this complaint record '
				. 'can be found published on the internet.'
		);
		$this->v["pcif"]->addFldID('complaints', 10269); // com_anon
		$this->v["pcif"]->addFldID('complaints', 245);   // inc_time_start
		$this->v["pcif"]->addFldID('complaints', 240);   // inc_address 
		$this->v["pcif"]->addFldID('complaints', 805);   // inc_address_city 
		$this->v["pcif"]->addFldID('complaints', 806);   // inc_address_state
		$this->v["pcif"]->addFldID('complaints', 807);   // inc_address_zip
		$this->v["pcif"]->addFldID('complaints', 789);   // com_summary
		$this->v["pcif"]->addFldID('complaints', 10292); // scn_how_feel
		$this->v["pcif"]->addFldID('complaints', 10293); // scn_desires_officers
		$this->v["pcif"]->addFldID('complaints', 10295); // scn_desires_depts
		$this->v["pcif"]->addFldID('complaints', 1151);  // com_anyone_charged
		$this->v["pcif"]->addFldID('complaints', 1124);  // com_all_charges_resolved

		$this->v["pcif"]->addTable('departments', 'department', 'links_complaint_dept');
		$this->v["pcif"]->addFldID('departments', 422);  // dept_name
		$this->v["pcif"]->addFldID('departments', 622);  // dept_address_city
		$this->v["pcif"]->addFldID('departments', 623);  // dept_address_state

		$this->v["pcif"]->addTable('officers', 'officer', 'officers');
		$this->v["pcif"]->addFldID('officers', 397);     // off_role
		$this->v["pcif"]->addFldID('officers', 1006);    // off_duty_status 
		$this->v["pcif"]->addFldID('officers', 1220);    // prsn_name_first
		$this->v["pcif"]->addFldID('officers', 1222);    // prsn_name_last
		$this->v["pcif"]->addFldID('officers', 1339);    // off_badge_number
		$this->v["pcif"]->addFldID('officers', 1169);    // phys_race_race 
		$this->v["pcif"]->addFldID('officers', 628);     // phys_gender 
		$this->v["pcif"]->addFldID('officers', 629);     // phys_age

		$this->v["pcif"]->addTable('civilians', 'civilian', 'civilians');
		$this->v["pcif"]->addFldID('civilians', 990);    // civ_role
		$this->v["pcif"]->addFldID('civilians', 1220);   // prsn_name_first
		$this->v["pcif"]->addFldID('civilians', 1222);   // prsn_name_last
		$this->v["pcif"]->addFldID('civilians', 1169);   // phys_race_race 
        $this->v["pcif"]->addFldID('civilians', 628);    // phys_gender 
        $this->v["pcif"]->addFldID('civilians', 1114);   // phys_gender_other
		$this->v["pcif"]->addFldID('civilians', 629);    // phys_age
		$this->v["pcif"]->addFldID('civilians', 1233);   // prsn_birthday
		$this->loadPcifSchemaAllegOP();
		if (!$opOnly) {
			$this->loadPcifSchemaAllegRaheem();
		}
		return true;
	}

 	// OpenPolice.org Allegations
	public function loadPcifSchemaAllegOP()
	{
		$this->v["pcif"]->addTable('openpolice_allegations', 'openpolice_allegation', 'alleg_silver', true);
		$this->v["pcif"]->addFldID('openpolice_allegations', 1093); // alle_sil_stop_wrongful
		$this->v["pcif"]->addFldID('openpolice_allegations', 1095); // alle_sil_officer_refuse_id
		$this->v["pcif"]->addFldID('openpolice_allegations', 1097); // alle_sil_search_wrongful
		$this->v["pcif"]->addFldID('openpolice_allegations', 1099); // alle_sil_property_wrongful
		$this->v["pcif"]->addFldID('openpolice_allegations', 1440); // alle_sil_property_damage
		$this->v["pcif"]->addFldID('openpolice_allegations', 1399); // alle_sil_sexual_harass
		$this->v["pcif"]->addFldID('openpolice_allegations', 1108); // alle_sil_sexual_assault
		$this->v["pcif"]->addFldID('openpolice_allegations', 1101); // alle_sil_force_unreason
		$this->v["pcif"]->addFldID('openpolice_allegations', 812);  // alle_sil_intimidating_weapon
		$this->v["pcif"]->addFldID('openpolice_allegations', 1103); // alle_sil_arrest_wrongful
		$this->v["pcif"]->addFldID('openpolice_allegations', 1106); // alle_sil_arrest_retaliatory
		$this->v["pcif"]->addFldID('openpolice_allegations', 1165); // alle_sil_citation_excessive
		$this->v["pcif"]->addFldID('openpolice_allegations', 1109); // alle_sil_bias
		$this->v["pcif"]->addFldID('openpolice_allegations', 1563); // alle_sil_repeat_harass
		$this->v["pcif"]->addFldID('openpolice_allegations', 1117); // alle_sil_neglect_duty 
		$this->v["pcif"]->addFldID('openpolice_allegations', 1107); // alle_sil_procedure
		$this->v["pcif"]->addFldID('openpolice_allegations', 1111); // alle_sil_unbecoming
		$this->v["pcif"]->addFldID('openpolice_allegations', 1112); // alle_sil_discourteous
		$this->v["pcif"]->fldNameStrReplace('alle_sil_', '');
		return true;
	}

 	// Raheem.ai Allegations
	public function loadPcifSchemaAllegRaheem()
	{
		$this->v["pcif"]->addTable('raheem_allegations', 'raheem_allegation');
		$enums = [
			'helped', 'protected', 'profiled', 'neglected', 'harassed', 
			'wrongly accused', 'disrespected', 'physically attacked'
		];
		$this->v["pcif"]->addFld(
			'raheem_allegations', 
			'encounter_type', 
			'Type of Encounter', 
			'Indicates the general type of encounter documented by this record.',
			$enums
		);
		$enums = [ 'Y', 'N' ];
		$this->v["pcif"]->addFld(
			'raheem_allegations', 
			'force_or_weapon', 
			'Use Physical Force or Point Weapon?', 
			'Did police use physical force against you or point their weapon at you?',
			$enums
		);
		$this->v["pcif"]->addFld(
			'raheem_allegations', 
			'verbal_threats', 
			'Threatened Verbally or Intimidated?', 
			'Did police threaten you verbally, use foul language, or intimidate you during this encounter?',
			$enums
		);
		$this->v["pcif"]->addFld(
			'raheem_allegations', 
			'money_property', 
			'Money/Property Seized/Destroyed, or Excessive Fine?', 
			'Did the police take money, take or destroy property, or issue an excessive fine during this encounter?',
			$enums
		);
		$this->v["pcif"]->addFld(
			'raheem_allegations', 
			'neglect_duty', 
			'Neglect of Duty?', 
			'Did police fail to help you when you needed it?',
			$enums
		);
		return true;
	}

    public function overrideRecordValueCustomAPI($name = 'custom_api', $type = 'xml', $tbl = '', $rec = null, $apiFld = null)
    {
    	if ($name == 'pcif') {
    		if ($tbl == 'complaints' 
    			&& isset($this->sessData->dataSets[$tbl])) {
    			$id = $this->sessData->dataSets[$tbl][0]->com_public_id;
    			if ($apiFld->label == 'pcif_id') {
                    return 'OP' . $id;
    			} elseif ($apiFld->label == 'url') {
                    return 'https://openpolice.org/complaint/read-' . $id;
    			}
    		} elseif ($tbl == 'civilians' 
    			&& $apiFld->label == 'birthday'
    			&& isset($this->sessData->dataSets[$tbl])
    			&& isset($rec->civ_birthday)
    			&& trim($rec->civ_birthday) != '') {
    			return date("Y", strtotime($rec->civ_birthday));
    		}
    	}
    	return '';
    }
    
    protected function genXmlFormatValCustomPerms($rec, $fld, $abbr)
    {
    	if ($abbr == 'com_') {
    		if ($fld->fld_name == 'summary'
    			&& $this->canPrintFullReportByRecordSpecs()) {
    			return true;
    		} elseif ($fld->fld_name == 'address'
    			&& $this->canPrintIncidentLocation()) {
    			return true;
    		}
    	}
    	if ($abbr == 'off_') {
    		if (in_array($fld->fld_name, ['name_first', 'name_last', 'badge_number'])
    			&& $this->canPrintOfficersName($this->sessData->dataSets["complaints"][0])
    			&& $this->corePublishStatuses($this->sessData->dataSets["complaints"][0])) {
    			return true;
    		}
    	}
    	if ($abbr == 'civ_') {
    		if (in_array($fld->fld_name, ['name_first', 'name_last'])
    			&& $this->canPrintComplainantName($this->sessData->dataSets["complaints"][0])
    			&& $this->corePublishStatuses($this->sessData->dataSets["complaints"][0])) {
    			return true;
    		}
    	}
        return false;
    }

}