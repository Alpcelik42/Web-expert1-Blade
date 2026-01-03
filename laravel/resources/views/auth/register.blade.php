{{-- resources/views/auth/register.blade.php --}}
    <!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - EventHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .register-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 500px;
            margin: 0 auto;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="register-card">
        <div class="text-center mb-4">
            <h2>Registreren</h2>
            <p class="text-muted">Maak een nieuw EventHub account</p>
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

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Naam</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mailadres</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Wachtwoord</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Minimaal 8 karakters</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Bevestig wachtwoord</label>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Telefoonnummer (optioneel)</label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Adres (optioneel)</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                           id="address" name="address" value="{{ old('address') }}">
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BEVEILIGINGSVRAAG VELDEN - DEZE MIS JE -->
            <div class="mb-3">
                <label for="security_question" class="form-label">Beveiligingsvraag</label>
                <select class="form-control @error('security_question') is-invalid @enderror"
                        id="security_question" name="security_question" required>
                    <option value="">Selecteer een vraag</option>
                    <option value="Wat is de naam van je eerste huisdier?" {{ old('security_question') == 'Wat is de naam van je eerste huisdier?' ? 'selected' : '' }}>
                        Wat is de naam van je eerste huisdier?
                    </option>
                    <option value="Wat is de geboorteplaats van je moeder?" {{ old('security_question') == 'Wat is de geboorteplaats van je moeder?' ? 'selected' : '' }}>
                        Wat is de geboorteplaats van je moeder?
                    </option>
                    <option value="Wat is je favoriete boek?" {{ old('security_question') == 'Wat is je favoriete boek?' ? 'selected' : '' }}>
                        Wat is je favoriete boek?
                    </option>
                    <option value="Wat was de naam van je eerste school?" {{ old('security_question') == 'Wat was de naam van je eerste school?' ? 'selected' : '' }}>
                        Wat was de naam van je eerste school?
                    </option>
                    <option value="Wat is je favoriete vakantiebestemming?" {{ old('security_question') == 'Wat is je favoriete vakantiebestemming?' ? 'selected' : '' }}>
                        Wat is je favoriete vakantiebestemming?
                    </option>
                    <option value="custom">Eigen vraag...</option>
                </select>
                @error('security_question')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="custom_question_container" style="display: none;">
                <label for="custom_security_question" class="form-label">Eigen beveiligingsvraag</label>
                <input type="text" class="form-control @error('custom_security_question') is-invalid @enderror"
                       id="custom_security_question" name="custom_security_question"
                       value="{{ old('custom_security_question') }}">
                @error('custom_security_question')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="security_answer" class="form-label">Antwoord op beveiligingsvraag</label>
                <input type="text" class="form-control @error('security_answer') is-invalid @enderror"
                       id="security_answer" name="security_answer"
                       value="{{ old('security_answer') }}" required>
                @error('security_answer')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Dit wordt gebruikt om je identiteit te verifiëren bij wachtwoordherstel.</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    Ik ga akkoord met de
                    <a href="#" class="text-decoration-none">gebruiksvoorwaarden</a>
                </label>
                @error('terms')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-register mb-3">Account aanmaken</button>

            <div class="text-center">
                <p class="mb-0">Al een account?</p>
                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                    Inloggen
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const securityQuestionSelect = document.getElementById('security_question');
        const customContainer = document.getElementById('custom_question_container');

        // Functie om custom vraag container te tonen/verbergen
        function toggleCustomQuestion() {
            if (securityQuestionSelect.value === 'custom') {
                customContainer.style.display = 'block';
                // Zet eigen vraag als required
                document.getElementById('custom_security_question').required = true;
            } else {
                customContainer.style.display = 'none';
                // Zet eigen vraag niet required
                document.getElementById('custom_security_question').required = false;
            }
        }

        // Luister naar wijzigingen in de select
        securityQuestionSelect.addEventListener('change', toggleCustomQuestion);

        // Controleer bij het laden van de pagina
        toggleCustomQuestion();

        // Controleer of er een oude waarde is voor custom vraag
        @if(old('security_question') === 'custom')
            customContainer.style.display = 'block';
        @endif
    });
</script>
</body>
</html>
