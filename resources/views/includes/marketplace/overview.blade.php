<div class="row" id="update">
    @foreach ($projects as $project)
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">

                    <a href="{{ url('projects/' . $project->slug) }}">
                        <h3>{{ $project->name }}</h3>
                    </a>

                    <p>
                        <a href="{{ url('projects/' . $project->slug) }}">
                            <img src="{{ URL::asset('images/25.jpg') }}" alt="img25"/>
                        </a>
                    </p>
                    <p>Anzahl der Personen: {{ $project->participants_capacity }}</p>
                    <p>Anzahl der SCP: {{ $project->scp_reward }}</p>
                    <p>Betreuer: {{ $project->tutor }}</p>
                </div>
            </div>
        </div>
    @endforeach


    @if($projects == [])
        <p>Keine Projekte vorhanden!</p>
    @endif
</div>
