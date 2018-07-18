<!-- resources/views/vendor/openpolice/inc-footer-admin.blade.php -->

<div id="admFootLegal">
    <a @if (!isset($isPrint) || !$isPrint) href="//creativecommons.org/licenses/by-sa/3.0/" target="_blank" @endif 
        ><img src="/survloop/creative-commons-by-sa-88x31.png" border=0 align=left class="mT5 mR10" ></a>
    <i>All specifications for database designs and user experience (form tree map) are made available<br />
    by <a @if (!isset($isPrint) || !$isPrint) href="http://FlexYourRights.org" target="_blanK" @endif 
        >Flex Your Rights</a> under the
    <a @if (!isset($isPrint) || !$isPrint) href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank" @endif 
        >Creative Commons Attribution-ShareAlike License</a>, {{ date("Y") }}.</i>
</div>