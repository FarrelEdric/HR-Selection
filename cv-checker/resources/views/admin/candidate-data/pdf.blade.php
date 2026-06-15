<!DOCTYPE html>
<html>
<head>
    <title>Candidate Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; font-weight: bold; }
        h2 { text-align: center; color: #333; }
        .header { margin-bottom: 20px; }
        .date { text-align: right; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Candidate Data Report</h2>
        <div class="date">Exported on: {{ date('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Name</th>
                <th>Phone</th>
                <th>City</th>
                <th>Email</th>
                <th>Vote</th>
                <th>Summarize</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidates as $index => $candidate)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $candidate->created_at->format('d/m/Y') }}</td>
                <td><strong>{{ $candidate->name }}</strong></td>
                <td>{{ $candidate->phone }}</td>
                <td>{{ $candidate->city }}</td>
                <td>{{ $candidate->email }}</td>
                <td>{{ $candidate->vote ?? 'N/A' }}</td>
                <td>{{ $candidate->summarize ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
