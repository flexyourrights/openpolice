<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-staff-tools-email-redir.blade.php -->

<script type="text/javascript"> $(document).ready(function(){

    setTimeout(function() {
        if (document.getElementById("complaintToolbox")) {
            document.getElementById("complaintToolbox").innerHTML = getSpinner();
            window.scrollTo(0, 0);
console.log('1712-report-inc-staff-tools-email-redir');
            $("#complaintToolbox").load("/complaint-toolbox?cid={{ $coreID }}&ajax=1&refresh=1&emailSent={{ $emaID }}");
        }
    }, 10);

}); </script>

