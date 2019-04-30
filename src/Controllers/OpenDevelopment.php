<?php
namespace OpenPolice\Controllers;

use OpenPolice\Controllers\OpenReportTools;

class OpenDevelopment extends OpenReportTools
{
    protected function printNavDevelopmentArea($nID)
    {
        $docuNav = [
            [
                'Getting Started...', 
                [
                    ['/how-to-install-open-police-complaints-with-docker', 'How To Install OPC with Docker']
                ]
            ] /* ,
            [
                'Open Police Codebase Orientation...',
                [
                    ['/package-files-folders-classes', 'Package Files, Folders, and Classes'],
                    ['/developer-work-flows', 'Developer Work Flows']
                ]
            ] */
        ];
        return view('vendor.openpolice.inc-documentation-navigation', [
            "docuNav"  => $docuNav,
            "currPage" => $this->getCurrPage()
        ])->render();
    }
    
}