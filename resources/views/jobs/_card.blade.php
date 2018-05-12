<li class="job-card {{ $job->featured ? 'featured' : '' }}">
    <a href="/job/{{ $job->id }}">
        @if ($job->company_logo)
            <img class="job-card__image" src="{{ $job->company_logo }}" alt="{{ $job->company }}">
        @else
            <div class="placeholder-image">
                <span>
                    {{ substr($job->company, 0, 1) }}
                </span>
            </div>
        @endif

        <div class="job-card__content">
            <h3>{{ $job->title }}</h3>
            <div class="job-card__meta">
                @if ($job->company)
                    <p>@svg('home') {{ $job->company }}</p>
                @endif
                @if ($job->location)
                    <p>@svg('pin') {{ $job->location }}</p>
                @endif
                @if ($job->salary)
                    <p>@svg('dollar') {{ $job->salary }}</p>
                @endif
                @if ($job->type)
                    <p>@svg('hourglass') {{ $job->type }}</p>
                @endif
            </div>
        </div>

        <span class="job-card__timeago hide-mobile">
            {{ $job->created_at->diffForHumans(null, true, true) }}
        </span>

    </a>
</li>
