@foreach($studentsOfSgl as $key => $student)
<tr>
    <td>{{ $student->last_name }}</td>
    <td>{{ $student->first_name }}</td>
    <td>

    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="{{ $student->scp_reward }}" aria-valuemin="0" aria-valuemax="{{ $student->workload }}" style="width: <?=$student->scp_reward/$student->workload*100 ?>%;"></div>
    </div>
    {{ $student->scp_reward }}h von {{ $student->workload }}h
    </td>
    <td>{{ $student->abbreviation }}</td>
</tr>
@endforeach
