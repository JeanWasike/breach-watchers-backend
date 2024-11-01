<!DOCTYPE html>
<html>
<head>
    <title>Compliance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Compliance Report for User ID: {{ $user_id }}</h1>
    <table>
        <thead>
            <tr>
                <th>Stage</th>
                <th>Question</th>
                <th>Your Answer</th>
                <th>Compliance Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($compliance_report as $report)
                <tr>
                    <td>{{ $report['stage'] }}</td>
                    <td>{{ $report['question'] }}</td>
                    <td>{{ implode(', ', $report['selected_choices']) }}</td>
                    <td>{{ $report['is_compliant'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
