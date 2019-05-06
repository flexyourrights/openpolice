<?php
namespace OpenPolice\Controllers;

use OpenPolice\Controllers\OpenReportTools;

class OpenDevelopment extends OpenReportTools
{
    protected function printNavDevelopmentArea($nID)
    {
        $docuNav = [
            [
                'About the Web App', 
                [
                    ['/web-app-workflows', 'Web App Workflows']
                ]
            ], [
                'Getting Started', 
                [
                    ['/how-to-install-open-police-complaints-with-docker', 'Install Copy of OPC with Docker', [
                        ['#quick',     'Install Commands'],
                        ['#video',     'Video'],
                        ['#reference', 'Reference']
                        ] ],
                    ['/web-app-technical-specs', 'General Technical Background on App']
                ]
            ], [
                'Open Police Codebase Orientation',
                [
                    ['https://survloop.org/package-files-folders-classes', 'Package Files, Folders, and Classes']
                ]
            ]
        ];
        return view('vendor.openpolice.inc-documentation-navigation', [
            "docuNav"  => $docuNav,
            "currPage" => $this->getCurrPage()
        ])->render();
    }
    
}