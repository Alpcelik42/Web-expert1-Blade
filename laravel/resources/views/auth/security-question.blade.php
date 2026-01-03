<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beveiligingsvraag - EventHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .security-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 400px;
            margin: 0 auto;
        }
        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-weight: bold;
        }
        .question-box {
            background-color: #f8f9fa;
            border-left: 4px solid #4361ee;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="security-card">
        <div class="text-center mb-4">
            <h2>Identiteitsverificatie</h2>
            <p class="text-muted">Beantwoord je beveiligingsvraag om verder te gaan</p>
            <p><small>E-mail: <strong>{{ $email }}</strong></small></p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="question-box">
            <p><strong>Vraag:</strong> {{ $security_question }}</p>
        </div>

        <form method="POST" action="{{ route('password.verify.answer') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
                <label for="security_answer" class="form-label">Jouw antwoord</label>
                <input type="text" class="form-control @error('security_answer') is-invalid @enderror"
                       id="security_answer" name="security_answer" required autofocus>
                @error('security_answer')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Let op hoofdletters/kleine letters!</small>
            </div>

            <button type="submit" class="btn btn-verify mb-3">Antwoord verifiëren</button>

            <div class="text-center">
                <a href="{{ route('password.request') }}" class="text-decoration-none">
                    Terug naar e-mail invoeren
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
