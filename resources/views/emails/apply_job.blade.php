<p>Applicant profile:</p>
<p> - Name:  {!! $user->firstname . ' ' . $user->lastname !!}</p>
<p> - Email:  {!! $user->email !!}</p>
<p> - Birthday:  {!! $user->dob !!}</p>
<p> - Country:  {!! $user->country !!}</p>
------------------------------------------------------------------------------------------
<p>Job infomation:</p>
<p> - Position: {{ $job->title }}</p>
<p> - Country: {{ $job->country }}</p>
<p> - Location: {{ $job->location }}</p>