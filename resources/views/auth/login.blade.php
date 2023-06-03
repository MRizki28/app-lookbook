@extends('Layouts.loginBase')
@section('content')
    <div class="login-card">
        <img src="{{ asset('img/logo1.png') }}" style="max-width: 150px" alt=""><br><br><br>
        <div id="error-message" class="error-message"></div>
        <form class="login-form" id="login-form">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="email" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <button>Login</button>
        </form>
    </div>

    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var formTambah = $('#login-form');
            var errorMessage = $('#error-message');

            formTambah.on('submit', function(e) {
                e.preventDefault();
                errorMessage.empty();

                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ url('v1/cms/login') }}',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.message === 'Invalid email or password') {
                            var error = data.errors;
                            var errorMessageText = "Email or password not valid";

                            $.each(error, function(key, value) {
                                errorMessageText += value[0] + "<br>";
                            });

                            showErrorAlert(errorMessageText);
                        } else {
                            console.log(data);
                            localStorage.setItem('access_token', data.access_token);
                            showSuccessAlert('Success login', '/lookbook');
                        }
                    },
                    error: function(data) {
                        var error = data.responseJSON.errors;
                        var errorMessageText = "";

                        $.each(error, function(key, value) {
                            errorMessageText += value[0] + "<br>";
                        });

                        showErrorAlert(errorMessageText);
                    }
                });
            });

            function showErrorAlert(message) {
                errorMessage.html(message);
            }

            function showSuccessAlert(message, redirectUrl) {
                alert(message);
                window.location.href = redirectUrl;
            }
        });
    </script>
@endsection
