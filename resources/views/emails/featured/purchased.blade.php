@component('mail::message')
# Thank you for purchasing a featured job listing

We have processed your order, and added the job listing to the database.

The job is active now and the next 30 days.

@component('mail::button', ['url' => "/job/{$job->id}"])
View job
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
