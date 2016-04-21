<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF Export</title>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css" />
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <style type="text/css">

    h1 {
        font-size: 18px;
        font-weight: bold;
    }
    .table-info {
        width: 70%;
    }

    .table-info td {
        padding-right: 30px;
        font-size: 12px;
    }

    .table {
        font-size: 10px;
    }

    .table-scp {
        border: 0;
    }

    .table-scp th {
        background-color: #dff0d8;
    }

    .table-scp .gutter + th,
    .table-scp .gutter + th + th {
        background-color: #f2dede;
    }

    .table-scp td,
    .table-scp th {
        padding: 1% !important;
    }

    .table-scp th[colspan="3"] + th,
    .table-scp td:nth-child(7),
    .table-scp td[colspan="3"] + td,
    .table-scp td[colspan="6"] + td {
        border: 0;
        background-color: #FFF;
    }

    .table-scp td.bg_green {
        background-color: #dff0d8;
    }

    .table-scp td.bg_red {
        background-color: #f2dede;
    }

    .table-scp td.sign {
        border: 1px solid #ddd;
    }

    .checkbox {
        line-height: 2.1;
    }

    .result {
        font-weight: bold;
    }

    .numbers ~ td {
        text-align: center;
    }
    </style>
</head>
<body>
    <h1>Social Credits - Abrechnung</h1>
    <table class="table-info">
        <tr>
            <td>Campus</td>
            <td>{{ $course->campus }}</td>
        </tr>
        <tr>
            <td>Studiengang</td>
            <td>{{ $course->name }}</td>
        </tr>
        <tr>
            <td>Kurs</td>
            <td>{{ $course->abbreviation }}</td>
        </tr>
        <tr>
            <td>Studienjahr</td>
            <td><?=  date_parse($academic_year->start_date)['year']?> / <?=date_parse($academic_year->end_date)['year']?></td>
        </tr>
        <tr>
            <td>Modul</td>
            <td>Key Qualifications I / Schlüsselqualifikationen I (WMEON_611)</td>
        </tr>
        <tr>
            <td>Unit</td>
            <td>Social Credits (WMEON_600.18)</td>
        </tr>
        <tr>
            <td>Soll-Workload</td>
            <td>{{ $academic_year->workload }} h</td>
        </tr>
        <tr>
            <td>Name</td>
            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
        </tr>
        <tr>
            <td>Matrikelnummer</td>
            <td>{{ $student->matriculation_number }}</td>
        </tr>
    </table>
    <br>
    <table class="table table-bordered table-striped table-scp">
        <colgroup>
            <col width="4%">
            <col width="10%">
            <col width="27%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="1%">
            <col width="10%">
            <col width="17%">
        </colgroup>
        <tr>
            <th rowspan="2">Lfd Nr.</th>
            <th rowspan="2">Betreuer / Ansprech- partner Hochschule</th>
            <th rowspan="2">Tätigkeit / Projekt / Aktivität (Details in den Dokus beiliegend)</th>
            <th colspan="3">Anzahl Stunden</th>
            <th rowspan="2" class="gutter"></th>
            <th rowspan="2">Anerkannte Anzahl Stunden (falls abweichend)</th>
            <th rowspan="2">Unterschrift Betreuer / Ansprechpartner (Anerkennung, Freigabe und Doku-Ablage)</th>
        </tr>
        <tr>
            <th>Engagement Studiengang </th>
            <th>Engagement Hochschule</th>
            <th>Engagement Extern</th>
        </tr>
        @include ('includes.user.project_table')
        <tr>
            <td colspan="6"></td>
            <td></td>
            <td colspan="2">
                <div class="checkbox">
                    <label>
                      <input type="checkbox"> Leistungsnachweis Unit erfüllt
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                      <input type="checkbox"> Leistungsnachweis Unit nicht erfüllt
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">Datum und Unterschrift Studierende/r</td>
            <td class="bg_green sign"></td>
            <td colspan="3"></td>
            <td></td>
            <td>Datum und Unterschrift SGL</td>
            <td class="bg_red sign"></td>
        </tr>
    </table>
</body>
</html>