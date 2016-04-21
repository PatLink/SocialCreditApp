<?php
$summe_engagement_studiengang = 0;
$summe_engagement_hochschule = 0;
$summe_engagement_extern = 0;
?>

@foreach ($projects as $id => $project)
<tr>
    <td>{{ $id + 1 }}</td>
    <td>{{ $project->tutor }}</td>
    <td class="numbers">{{ $project->name }}</td>
    <td>
        @if($project->engagement == 'Studiengang')
            {{ $project->scp_reward }}
            <?php $summe_engagement_studiengang += $project->scp_reward; ?>
        @endif
    </td>
    <td>
        @if($project->engagement == 'Hochschule')
            {{ $project->scp_reward }}
            <?php $summe_engagement_hochschule += $project->scp_reward; ?>
        @endif
    </td>
    <td>
        @if($project->engagement == 'Extern')
            {{ $project->scp_reward }}
            <?php $summe_engagement_extern += $project->scp_reward; ?>
        @endif
    </td>
    <td class="ausblenden"></td>
    <td></td>
    <td></td>
</tr>
@endforeach
<tr class="result">
    <td></td>
    <td></td>
    <td class="numbers"><strong>Einzelsummen</strong></td>
    <td><?= $summe_engagement_studiengang ?></td>
    <td><?= $summe_engagement_hochschule ?></td>
    <td><?= $summe_engagement_extern ?></td>
    <td class="ausblenden"></td>
    <td rowspan="2"></td>
    <td></td>
</tr>
<tr class="result">
    <td></td>
    <td></td>
    <td class="numbers"><strong>Gesamtsumme</strong></td>
    <td colspan="3"><?= $summe_engagement_studiengang + $summe_engagement_hochschule + $summe_engagement_extern ?></td>
    <td class="ausblenden"></td>
    <td></td>
</tr>
