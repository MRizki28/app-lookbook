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
        <label for="password">New password</label>
        <input type="password" name="password" id="password">
        <label for="password">Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation">

        <button type="submit">Submit</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(function() {
            var resetPasswordToken = getResetPasswordTokenFromUrl();

            $('#forgot-password-form').data('reset_password_token', resetPasswordToken);

            $('#forgot-password-form').submit(function(event) {
                event.preventDefault();

                var password = $('#password').val();
                var token = $('meta[name="csrf-token"]').attr('content');
                var password_confirmation = $('#password_confirmation').val();
                var reset_password_token = $(this).data('reset_password_token');

                $.ajax({
                    url: "{{ url('api/v5/success/password') }}/" + reset_password_token,
                    type: 'POST',
                    data: {
                        password: password,
                        password_confirmation: password_confirmation,
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
                            alert = 'success send password check your password';
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

            function getResetPasswordTokenFromUrl() {
                var url = window.location.href;
                var token = url.split('/').pop();
                return token;
            }
        });
    </script>
</body>

</html>
