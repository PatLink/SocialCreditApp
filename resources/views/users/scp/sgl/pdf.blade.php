<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF Export</title>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css" />
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <style type="text/css">
        .progress {
            display: none;
        }
    </style>
</head>
<body>
    <h3>Kursübersicht</h3>

    <table class="table">
        <tr class="header">
            <th>Name</th>
            <th>Vorname</th>
            <th>Social Credit Points</th>
            <th>Kurs</th>
        </tr>
    @include ('includes.user.students_table')
    </table>
</body>
</html>
