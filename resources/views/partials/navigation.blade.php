<nav>
    <a href="/" class="{{ (request()->input('filter') === null) ? 'active' : '' }}">All</a>
    <a href="/?filter=full-time" class="{{ (request()->input('filter') === 'full-time') ? 'active' : '' }}">Full Time</a>
    <a href="/?filter=part-time" class="{{ (request()->input('filter') === 'part-time') ? 'active' : '' }}">Part Time</a>
    <a href="/?filter=internship" class="{{ (request()->input('filter') === 'internship') ? 'active' : '' }}">Internship</a>
    <a href="/?filter=freelance" class="{{ (request()->input('filter') === 'freelance') ? 'active' : '' }}">Freelance</a>
    <a href="/?filter=temporary" class="{{ (request()->input('filter') === 'temporary') ? 'active' : '' }}">Temporary</a>
</nav>
