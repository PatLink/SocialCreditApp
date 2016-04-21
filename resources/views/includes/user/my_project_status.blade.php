@if($project['status'] == 'draft')
    <p>Draft</p>
@elseif ($project['status'] == 'unbestätigt')
    <p>Wird überprüft</p>
@elseif ($project['status'] == 'bestätigt')
    <p>Angenommen</p>
@elseif ($project['status'] == 'abgeschlossen')
    <p>Abgeschlossen</p>
@elseif ($project['status'] == 'abgelehnt')
    <p>Abgelehnt</p>
@endif
