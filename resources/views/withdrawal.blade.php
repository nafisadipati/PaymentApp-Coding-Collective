<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal</title>
</head>
<body>
    <a href="{{ route('dashboard') }}">Go to Dashboard</a>
    <h2>Withdrawal Form</h2>

    <form id="withdrawalForm">
        @csrf
        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" step="0.01" required>
        <br><br>

        <button type="submit">Withdraw</button>
    </form>

    <div id="responseMessage"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#withdrawalForm').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: '{{ route('withdrawal.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#responseMessage').html('<p style="color: green;">' + response.message + '</p>');
                },
                error: function(xhr) {
                    $('#responseMessage').html('<p style="color: red;">' + xhr.responseJSON.message + '</p>');
                }
            });
        });
    </script>
</body>
</html>
