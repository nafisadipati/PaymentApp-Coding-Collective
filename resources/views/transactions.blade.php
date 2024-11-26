<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<form id="logoutForm" action="{{ url('logout') }}" method="post" style="display: none;">
    @csrf
</form>

<a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Logout</a>

    <h1>Welcome, {{ auth()->user()->name }}</h1>
    <h2>Wallet Balance: <span id="walletBalance">Rp {{ number_format($wallet->balance, 2) }}</span></h2>

    <h3>Deposit</h3>
    <form id="depositForm">
        @csrf
        <label for="deposit_order_id">Order ID:</label>
        <input type="text" id="deposit_order_id" name="order_id" required>
        <label for="deposit_amount">Amount:</label>
        <input type="number" id="deposit_amount" name="amount" step="0.01" required>
        <button type="submit">Deposit</button>
    </form>

    <h3>Withdrawal</h3>
    <form id="withdrawalForm">
        @csrf
        <label for="withdrawal_amount">Amount:</label>
        <input type="number" id="withdrawal_amount" name="amount" step="0.01" required>
        <button type="submit">Withdraw</button>
    </form>

    <h3>Transfer</h3>
    <form id="transferForm">
        @csrf
        <label for="receiver_email">Receiver's Email:</label>
        <input type="email" id="receiver_email" name="receiver_email" required>
        <label for="transfer_amount">Amount:</label>
        <input type="number" id="transfer_amount" name="amount" step="0.01" required>
        <button type="submit">Transfer</button>
    </form>

    <h3>Transaction History</h3>
    <table id="transactionTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $transaction->order_id }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>Rp {{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->status === 1 ? 'Success' : 'Failed' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      
        $('#depositForm').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('deposit.store') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    updateUI(response.transaction, response.wallet_balance);
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        $('#withdrawalForm').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('withdrawal.store') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    updateUI(response.transaction, response.wallet_balance);
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });

        $('#transferForm').submit(function(e) {
            e.preventDefault();

            if (confirm("This transfer will incur an admin fee. Do you wish to proceed?")) {
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('transfer.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        updateUI(response.transaction, response.wallet_balance);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            }
        });

        function updateUI(transaction, walletBalance) {
            $('#walletBalance').text(`Rp ${walletBalance.toLocaleString('id-ID', { minimumFractionDigits: 2 })}`);
            $('#transactionTable tbody').prepend(`
                <tr>
                    <td>${transaction.created_at}</td>
                    <td>${transaction.order_id}</td>
                    <td>${transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1)}</td>
                    <td>Rp ${transaction.amount.toLocaleString('id-ID', { minimumFractionDigits: 2 })}</td>
                    <td>${transaction.status === 1 ? 'Success' : 'Failed'}</td>
                </tr>
            `);
        }
    });
    </script>
</body>
</html>
