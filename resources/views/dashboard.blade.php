<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Welcome, Admin</h1>

    <h2>Admin Fee: Rp {{ number_format($admin_fee->fee ?? 0, 2) }}</h2>

    <h3>Transaction History</h3>
    <table>
    <thead>
        <tr>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Amount</th>
            <th>Admin Fee</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
        <tr>
            <td>{{ $transaction->sender->name }}</td>
            <td>{{ $transaction->receiver->name }}</td>
            <td>Rp {{ number_format($transaction->amount, 2) }}</td>
            <td>Rp {{ number_format($transaction->admin_fee, 2) }}</td>
            <td>{{ $transaction->status }}</td>
        </tr>
        @endforeach
    </tbody>
    </table>

    <h3>Update Admin Fee</h3>
    <form method="POST" action="{{ route('admin.update.admin_fee') }}">
        @csrf
        <label for="admin_fee">New Admin Fee:</label>
        <input type="number" name="admin_fee" value="{{ $admin_fee->fee ?? 0 }}" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
