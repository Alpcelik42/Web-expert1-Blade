{{-- resources/views/bookings/payment-method.blade.php --}}
@extends('layouts.app')

@section('title', 'Betaalmethode Kiezen - ' . $booking->booking_number)

@section('content')
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="bi bi-credit-card"></i>
                                Betaalmethode Kiezen
                            </h4>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left"></i> Terug naar boeking
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Status banner -->
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock-history me-3 fs-4"></i>
                                <div>
                                    <strong class="d-block">Betaal je boeking af om je tickets te activeren</strong>
                                    <small class="d-block mt-1">Boekingnummer: <strong>{{ $booking->booking_number }}</strong> |
                                        Bedrag: <strong class="text-success">{{ $booking->formatted_total_price }}</strong></small>
                                </div>
                            </div>
                        </div>

                        <!-- Boeking samenvatting -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-receipt"></i> Boeking Overzicht</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">Evenement Details</h6>
                                        <h5>{{ $booking->event->title }}</h5>
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-calendar me-2"></i>
                                            {{ $booking->event->start_date->format('d-m-Y') }}
                                            om {{ $booking->event->start_date->format('H:i') }}
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-geo-alt me-2"></i>
                                            {{ $booking->event->location }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">Ticket Details</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>{{ $booking->ticket->type }} × {{ $booking->quantity }}</span>
                                            <span>{{ $booking->formatted_unit_price }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Totaal</span>
                                            <span class="fs-5 text-success">{{ $booking->formatted_total_price }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Betaalmethoden -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-wallet2"></i> Kies je betaalmethode</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('bookings.process-payment', $booking) }}" method="POST" id="payment-form">
                                    @csrf

                                    <div class="payment-methods">
                                        <!-- Ideal -->
                                        <div class="payment-method-card mb-3">
                                            <input type="radio" name="payment_method" value="ideal" id="ideal" class="d-none" checked>
                                            <label for="ideal" class="payment-method-label d-flex align-items-center p-3 border rounded cursor-pointer hover-shadow">
                                                <div class="payment-icon me-3">
                                                    <div class="bg-primary text-white rounded p-2">
                                                        <i class="bi bi-bank fs-4"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">iDEAL</h6>
                                                    <p class="text-muted mb-0">Betaal veilig via je eigen bank</p>
                                                </div>
                                                <div class="form-check">
                                                    <div class="form-check-circle">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </div>
                                                </div>
                                            </label>

                                            <!-- Ideal bank selectie -->
                                            <div class="ideal-banks mt-3 p-3 border rounded" id="ideal-banks">
                                                <h6 class="mb-3">Selecteer je bank</h6>
                                                <div class="row">
                                                    @php
                                                        $banks = [
                                                            'ABNANL2A' => 'ABN AMRO',
                                                            'ASNBNL21' => 'ASN Bank',
                                                            'BUNQNL2A' => 'bunq',
                                                            'INGBNL2A' => 'ING',
                                                            'RABONL2U' => 'Rabobank',
                                                            'SNSBNL2A' => 'SNS',
                                                            'TRIONL2U' => 'Triodos Bank',
                                                            'FVLBNL22' => 'van Lanschot',
                                                            'KNABNL2H' => 'Knab',
                                                            'REVOLT21' => 'Revolut',
                                                        ];
                                                    @endphp

                                                    @foreach($banks as $code => $name)
                                                        <div class="col-md-6 mb-2">
                                                            <input type="radio" name="ideal_bank" value="{{ $code }}" id="bank-{{ $code }}"
                                                                   class="d-none" {{ $loop->first ? 'checked' : '' }}>
                                                            <label for="bank-{{ $code }}"
                                                                   class="bank-option d-flex align-items-center p-2 border rounded cursor-pointer">
                                                                <i class="bi bi-bank me-2"></i>
                                                                <span>{{ $name }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Credit Card -->
                                        <div class="payment-method-card mb-3">
                                            <input type="radio" name="payment_method" value="creditcard" id="creditcard" class="d-none">
                                            <label for="creditcard" class="payment-method-label d-flex align-items-center p-3 border rounded cursor-pointer hover-shadow">
                                                <div class="payment-icon me-3">
                                                    <div class="bg-warning text-white rounded p-2">
                                                        <i class="bi bi-credit-card fs-4"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Creditcard</h6>
                                                    <p class="text-muted mb-0">Visa, Mastercard of American Express</p>
                                                </div>
                                                <div class="form-check">
                                                    <div class="form-check-circle">
                                                        <i class="bi bi-circle"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- PayPal -->
                                        <div class="payment-method-card mb-3">
                                            <input type="radio" name="payment_method" value="paypal" id="paypal" class="d-none">
                                            <label for="paypal" class="payment-method-label d-flex align-items-center p-3 border rounded cursor-pointer hover-shadow">
                                                <div class="payment-icon me-3">
                                                    <div class="bg-info text-white rounded p-2">
                                                        <i class="bi bi-paypal fs-4"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">PayPal</h6>
                                                    <p class="text-muted mb-0">Betaal veilig via PayPal</p>
                                                </div>
                                                <div class="form-check">
                                                    <div class="form-check-circle">
                                                        <i class="bi bi-circle"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Overboeking -->
                                        <div class="payment-method-card">
                                            <input type="radio" name="payment_method" value="bank_transfer" id="bank_transfer" class="d-none">
                                            <label for="bank_transfer" class="payment-method-label d-flex align-items-center p-3 border rounded cursor-pointer hover-shadow">
                                                <div class="payment-icon me-3">
                                                    <div class="bg-success text-white rounded p-2">
                                                        <i class="bi bi-arrow-left-right fs-4"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Overboeking</h6>
                                                    <p class="text-muted mb-0">Handmatig overboeken (kan 1-2 werkdagen duren)</p>
                                                </div>
                                                <div class="form-check">
                                                    <div class="form-check-circle">
                                                        <i class="bi bi-circle"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Betaalvoorwaarden -->
                                    <div class="alert alert-info mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                Ik ga akkoord met de <a href="#" target="_blank">algemene voorwaarden</a>
                                                en het <a href="#" target="_blank">privacybeleid</a> van EventHub.
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Betaal knop -->
                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-success btn-lg py-3" id="pay-button">
                                            <i class="bi bi-lock-fill"></i>
                                            <span id="pay-button-text">Betaal {{ $booking->formatted_total_price }} via iDEAL</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                            <i class="bi bi-x-circle"></i> Annuleren
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Veiligheid informatie -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-shield-check"></i> Veilige betaling</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4 mb-3">
                                        <div class="text-primary mb-2">
                                            <i class="bi bi-encrypt fs-1"></i>
                                        </div>
                                        <h6>SSL Versleuteling</h6>
                                        <p class="text-muted small">256-bit encryptie voor maximale veiligheid</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="text-success mb-2">
                                            <i class="bi bi-shield-lock fs-1"></i>
                                        </div>
                                        <h6>Geen opslag gegevens</h6>
                                        <p class="text-muted small">Wij slaan geen betaalgegevens op</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="text-warning mb-2">
                                            <i class="bi bi-award fs-1"></i>
                                        </div>
                                        <h6>Gecertificeerd</h6>
                                        <p class="text-muted small">PCI-DSS gecertificeerd</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-clock"></i>
                                Deze reservering is geldig tot: {{ now()->addHours(24)->format('d-m-Y H:i') }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-headset"></i>
                                Hulp nodig? <a href="mailto:support@eventhub.com">support@eventhub.com</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Payment method selection
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const payButtonText = document.getElementById('pay-button-text');
            const idealBanks = document.getElementById('ideal-banks');

            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    // Update form check circles
                    document.querySelectorAll('.form-check-circle i').forEach(icon => {
                        icon.className = 'bi bi-circle';
                    });

                    const selectedIcon = this.closest('.payment-method-card').querySelector('.form-check-circle i');
                    selectedIcon.className = 'bi bi-check-circle-fill text-success';

                    // Update button text
                    const methodName = this.closest('.payment-method-card').querySelector('h6').textContent;
                    payButtonText.textContent = `Betaal {{ $booking->formatted_total_price }} via ${methodName}`;

                    // Show/hide iDEAL banks
                    if (this.value === 'ideal') {
                        idealBanks.style.display = 'block';
                    } else {
                        idealBanks.style.display = 'none';
                    }
                });
            });

            // Bank selection for iDEAL
            const bankOptions = document.querySelectorAll('input[name="ideal_bank"]');
            bankOptions.forEach(bank => {
                bank.addEventListener('change', function() {
                    document.querySelectorAll('.bank-option').forEach(label => {
                        label.classList.remove('border-primary', 'bg-light');
                    });

                    const selectedLabel = this.closest('.bank-option');
                    selectedLabel.classList.add('border-primary', 'bg-light');
                });
            });

            // Form submission
            const paymentForm = document.getElementById('payment-form');
            paymentForm.addEventListener('submit', function(e) {
                if (!document.getElementById('terms').checked) {
                    e.preventDefault();
                    alert('Je moet akkoord gaan met de algemene voorwaarden om verder te gaan.');
                    return;
                }

                const submitBtn = document.getElementById('pay-button');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Bezig met verwerken...';
            });

            // Set initial state
            document.getElementById('ideal').dispatchEvent(new Event('change'));
            document.querySelector('input[name="ideal_bank"]:checked').dispatchEvent(new Event('change'));
        });
    </script>

    <style>
        /* Payment method styles */
        .payment-method-label {
            transition: all 0.3s ease;
            border: 2px solid #dee2e6;
        }

        .payment-method-label:hover {
            border-color: #4361ee;
            background-color: #f8f9ff;
        }

        input[name="payment_method"]:checked + .payment-method-label {
            border-color: #4361ee;
            background-color: #f8f9ff;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .form-check-circle {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-check-circle i {
            font-size: 1.25rem;
            color: #adb5bd;
        }

        .form-check-circle .bi-check-circle-fill {
            color: #198754 !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .hover-shadow:hover {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }

        /* Bank options */
        .bank-option {
            transition: all 0.2s ease;
        }

        input[name="ideal_bank"]:checked + .bank-option {
            border-color: #4361ee;
            background-color: #eef2ff;
        }

        .bank-option:hover {
            background-color: #f8f9fa;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .payment-method-label {
                flex-direction: column;
                text-align: center;
            }

            .payment-icon {
                margin-bottom: 10px;
            }

            .form-check {
                margin-top: 10px;
            }
        }

        /* Print styles */
        @media print {
            .btn, .payment-methods input, .payment-method-label, .bank-option {
                display: none !important;
            }

            .card {
                border: 1px solid #dee2e6 !important;
            }

            .alert {
                border: 1px solid #ffc107 !important;
            }
        }
    </style>
@endsection
