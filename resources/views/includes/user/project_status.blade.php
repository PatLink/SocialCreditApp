
@if($project->status == 'draft')
    <p>Draft
    	<span class="glyphicon glyphicon-pencil"></span>
    </p>
@elseif ($project->status == 'unbestätigt')
    <p>Wird überprüft
    	<span class="glyphicon glyphicon-hourglass"></span>
    </p>
@elseif ($project->status == 'bestätigt')
    <p>Angenommen
    	<span class="glyphicon glyphicon-ok"></span>
    </p>
@elseif ($project->status == 'abgeschlossen')
    <p>Abgeschlossen
    	<span class="glyphicon glyphicon-ban-circle"></span>
    </p>
@elseif ($project->status == 'abgelehnt')
    <p>Abgelehnt
    	<span class="glyphicon glyphicon-remove"></span>
    </p>
@endif
