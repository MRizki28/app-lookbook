<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
</head>

<body>
    <form id="forgot-password-form">
        @csrf
        <label for="email">Email</label>
        <input type="text" name="email" id="email">

        <button type="submit">Submit</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(function() {
            $('#forgot-password-form').submit(function(event) {
                event.preventDefault();

                var email = $('#email').val();
                var token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ url('api/v5/reset-password/') }}',
                    type: 'POST',
                    data: {
                        email: email,
                        _token: token
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            alert = 'success send email check your email';
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            title: 'Error!',
                            text: response.responseJSON.message ||
                                'An error occurred. Please try again later.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
