<?php
/**
  * OpenAjaxCachePrep is a mid-level class which handles AJAX requests
  * to pre-load various system caches to help optimize page load times.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.0.12
  */
namespace FlexYourRights\OpenPolice\Controllers;

use Illuminate\Http\Request;
use App\Models\OPComplaints;
use App\Models\SLLogActions;
use App\Models\SLSearchRecDump;
use FlexYourRights\OpenPolice\Controllers\OpenComplaintSaves;

class OpenAjaxCachePrep extends OpenComplaintSaves
{
    /**
     * Check complaints preloads.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string
     */
    public function ajaxComplaintPreloads(Request $request)
    {
        $coms = OPComplaints::where('com_public_id', '>', 0)
            ->select('com_id', 'com_public_id')
            ->orderBy('com_id', 'asc')
            ->get();
        //$comIDs = $GLOBALS["SL"]->resultsToArrIds($coms, 'com_id');
        if ($coms->isNotEmpty()) {
            foreach ($coms as $com) {
                $cachKey = 'page-complaint/read-' . $com->com_public_id
                    . '-visitor-c_' . $com->com_id . '-p_public.html';
                $content = $GLOBALS["SL"]->chkCache($cachKey, 'page', 42, $com->com_id);
                if (trim($content) == '') {
                    echo '/complaint/read-' . $com->com_public_id 
                        . '<br /><iframe src="/complaint/read-' . $com->com_public_id 
                        . '" style="width: 100%; height: 300px;"></iframe>' 
                        . $this->ajaxAgainComplaintPreloads();
                    exit;
                } else {
                    $dump = SLSearchRecDump::where('sch_rec_dmp_tree_id', 1)
                        ->where('sch_rec_dmp_perms', 'public')
                        ->where('sch_rec_dmp_rec_id', $com->com_id)
                        ->first();
                    if (!$dump || !isset($dump->sch_rec_dmp_id)) {
                        //$this->genRecDump($com->com_id, false);
                        $content = $this->ajaxComplaintPreloadSearchCache($content);
                        $dump = new SLSearchRecDump;
                        $dump->sch_rec_dmp_tree_id  = 1;
                        $dump->sch_rec_dmp_rec_id   = $com->com_id;
                        $dump->sch_rec_dmp_perms    = 'public';
                        $dump->sch_rec_dmp_rec_dump = $content;
                        try {
                            $dump->save();
                        } catch (Exception $e) {
                            $dump->sch_rec_dmp_rec_dump = '(error saving)';
                            $dump->save();
                            $log = new SLLogActions;
                            $log->log_database = $GLOBALS["SL"]->dbID;
                            $log->log_user     = $this->v["uID"];
                            $log->log_action   = 'Search Rec Dump';
                            $log->log_old_name = 'Tree 1';
                            $log->log_new_name = 'Rec ' . $com->com_id;
                            $log->save();
                        }
                        echo 'Search Cache ' . $com->com_id 
                            . $this->ajaxAgainComplaintPreloads();
                        exit;
                    }
                }
            }            
        }
        echo 'No cache prep needed.';
        exit;
    }

    /**
     * Run the complaints preloads check again.
     *
     * @return string
     */
    private function ajaxAgainComplaintPreloads()
    {
        return '<script type="text/javascript"> 
            setTimeout("window.location=\'/ajax/complaint-preloads\'", 30000);
            </script>';
    }

    /**
     * Check admin complaints preloads.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string
     */
    public function ajaxStaffComplaintPreloads(Request $request)
    {
        if (!$this->isStaffOrAdmin()) {
            exit;
        }
        $coms = OPComplaints::where('com_public_id', '>', 0)
            ->select('com_id', 'com_public_id')
            ->orderBy('com_id', 'desc')
            ->get();
        if ($coms->isNotEmpty()) {
            foreach ($coms as $com) {
                $cachKey = 'page-complaint/read-' . $com->com_public_id
                    . '/full?ajax=1&wdg=1-admin-c_' . $com->com_id . '-v_full-p_public.html';
                $content = $GLOBALS["SL"]->chkCache($cachKey, 'page', 42, $com->com_id);
                if (trim($content) == '') {
                    echo '/complaint/read-' . $com->com_public_id . '/full?ajax=1&wdg=1'
                        . '<br /><iframe src="/complaint/read-' . $com->com_public_id 
                        . '/full?ajax=1&wdg=1" style="width: 100%; height: 300px;"></iframe>' 
                        . $this->ajaxAgainStaffComplaintPreloads();
                    exit;
                } else {
                    $this->ajaxStaffComplaintPreloadDump($com->com_id, $content);
                }
            }
        }
        $coms = OPComplaints::whereNull('com_public_id')
            ->where('com_summary', 'NOT LIKE', '')
            ->whereNotNull('com_summary')
            ->select('com_id')
            ->orderBy('com_id', 'desc')
            ->get();
        if ($coms->isNotEmpty()) {
            foreach ($coms as $com) {
                $cachKey = 'page-complaint/readi-' . $com->com_id
                    . '/full?ajax=1&wdg=1-admin-c_' . $com->com_id . '-v_full-p_public.html';
                $content = $GLOBALS["SL"]->chkCache($cachKey, 'page', 42, $com->com_id);
                if (trim($content) == '') {
                    echo '/complaint/readi-' . $com->com_id . '/full?ajax=1&wdg=1'
                        . '<br /><iframe src="/complaint/readi-' . $com->com_id 
                        . '/full?ajax=1&wdg=1" style="width: 100%; height: 300px;"></iframe>' 
                        . $this->ajaxAgainStaffComplaintPreloads();
                    exit;
                } else {
                    $this->ajaxStaffComplaintPreloadDump($com->com_id, $content);
                }
            }
        }
        echo 'No cache prep needed.';
        exit;
    }

    /**
     * Shore admin complaints preload into search dump.
     *
     * @param  int $comID
     * @param  string $content
     * @return boolean
     */
    private function ajaxStaffComplaintPreloadDump($comID, $content)
    {
        $dump = SLSearchRecDump::where('sch_rec_dmp_tree_id', 1)
            ->where('sch_rec_dmp_perms', 'sensitive')
            ->where('sch_rec_dmp_rec_id', $comID)
            ->first();
        if (!$dump || !isset($dump->sch_rec_dmp_id)) {
            //$this->genRecDump($com->com_id, false);
            $content = $this->ajaxComplaintPreloadSearchCache($content);
            $dump = new SLSearchRecDump;
            $dump->sch_rec_dmp_tree_id  = 1;
            $dump->sch_rec_dmp_rec_id   = $comID;
            $dump->sch_rec_dmp_perms    = 'sensitive';
            $dump->sch_rec_dmp_rec_dump = $content;
            try {
                $dump->save();
            } catch (Exception $e) {
                $dump->sch_rec_dmp_rec_dump = '(error saving)';
                $dump->save();
                $log = new SLLogActions;
                $log->log_database = $GLOBALS["SL"]->dbID;
                $log->log_user     = $this->v["uID"];
                $log->log_action   = 'Search Rec Dump Staff';
                $log->log_old_name = 'Tree 1';
                $log->log_new_name = 'Rec ' . $comID;
                $log->save();
            }
            echo 'Search Cache ' . $comID
                . $this->ajaxAgainStaffComplaintPreloads();
            exit;
        }
        return true;
    }

    /**
     * Run the complaints preloads check again.
     *
     * @return string
     */
    private function ajaxAgainStaffComplaintPreloads()
    {
        return '<script type="text/javascript"> 
            setTimeout("window.location=\'/ajadm/complaint-preloads-staff\'", 15000);
            </script>';
    }

    /**
     * Convert complaint HTML into more searchable version.
     *
     * @param  string $content
     * @return string
     */
    private function ajaxComplaintPreloadSearchCache($str)
    {
        $pos = strpos($str, '<div id="nondialog">');
        if ($pos > 0) {
            $str = substr($str, $pos);
            $pos = strpos($str, '<!-- end nondialog -->');
            if ($pos > 0) {
                $str = substr($str, 0, $pos);
            }
        }
        $tag = '<td class="w50 datTdLab">';
        $str = $GLOBALS["SL"]->stripCertainHtmlTags($str, $tag, '</td>');
        $str = $GLOBALS["SL"]->stripAllSpaces($str);
        $str = strip_tags(str_replace('</', ' </', $str));
        $str = utf8_encode($str);
        return $str;
    }


}