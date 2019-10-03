<!-- resources/views/vendor/openpolice/report-inc-fill-glossary.blade.php -->

@if ($glossaryType == 'Submit Publicly')

    {{ $prvLnk }} User opts to publish the names of 
    civilians and police officers on this website.

@elseif ($glossaryType == 'No Names Public')

    {{ $prvLnk }} User doesn't want to publish any names on this website.
    This includes police officers\' names and badge numbers too.

@elseif ($glossaryType == 'Anonymous')

    {{ $prvLnk }} User needs complaint to be completely anonymous, even 
    though it will be harder to investigate. No names will be published 
    on this website. Neither OpenPolice.org staff nor investigators will 
    be able to contact them. Any details that could be used for personal 
    identification may be deleted from the database.

@elseif ($glossaryType == 'Gold-Level Complaint')

    <a href="/go-gold-make-your-complaint-strong">Optional</a>: 
    This user opted to share more complete details about 
    their police experience than a Basic Complaint.

@endif
