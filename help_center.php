<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Veloura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            background-color: #fff0f5;
            color: #333;
        }
        h1 {
            color: #d81b60;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #d81b6044;
        }
        h2 {
            color: #ad1457;
            font-size: 24px;
            font-weight: 600;
            margin-top: 32px;
            margin-bottom: 18px;
        }
        #faq .card {
            margin-bottom: 18px;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(216,27,96,0.10);
            border: none;
            background: linear-gradient(135deg, #fff 70%, #fce4ec 100%);
        }
        #faq .card-header {
            background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%);
            border-radius: 14px 14px 0 0;
            color: #fff;
            font-weight: 600;
            font-size: 18px;
        }
        #faq .btn-link {
            color: #fff;
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
            transition: color 0.2s;
        }
        #faq .btn-link:hover {
            color: #ad1457;
        }
        .card-body {
            background: #fff;
            color: #333;
            border-radius: 0 0 14px 14px;
            font-size: 16px;
        }
        ul {
            margin-top: 18px;
            font-size: 16px;
        }
        a {
            color: #d81b60;
            text-decoration: underline;
            transition: color 0.2s;
        }
        a:hover {
            color: #ad1457;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1>Help Center</h1>
        <p>Welcome to the Veloura Help Center. Here you can find answers to frequently asked questions and our contact information.</p>

        <hr>

        <h2>Frequently Asked Questions (FAQs)</h2>
        <div id="faq">
            <div class="card">
                <div class="card-header" id="faq-heading-1">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#faq-collapse-1" aria-expanded="true" aria-controls="faq-collapse-1">
                            What is your return policy?
                        </button>
                    </h5>
                </div>
                <div id="faq-collapse-1" class="collapse show" aria-labelledby="faq-heading-1" data-parent="#faq">
                    <div class="card-body">
                        Our return policy allows for returns within 30 days of purchase. Please visit our returns page for more details.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="faq-heading-2">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#faq-collapse-2" aria-expanded="false" aria-controls="faq-collapse-2">
                            How do I track my order?
                        </button>
                    </h5>
                </div>
                <div id="faq-collapse-2" class="collapse" aria-labelledby="faq-heading-2" data-parent="#faq">
                    <div class="card-body">
                        You can track your order using the tracking number sent to your email address after your purchase.
                    </div>
                </div>
            </div>
             <div class="card">
                <div class="card-header" id="faq-heading-3">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#faq-collapse-3" aria-expanded="false" aria-controls="faq-collapse-3">
                            What payment methods do you accept?
                        </button>
                    </h5>
                </div>
                <div id="faq-collapse-3" class="collapse" aria-labelledby="faq-heading-3" data-parent="#faq">
                    <div class="card-body">
                        We accept all major credit cards, PayPal, and other secure payment methods.
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <h2>Contact Information</h2>
        <p>If you have any other questions, please feel free to contact us:</p>
        <ul>
            <li>Email: <a href="mailto:support@veloura.com">support@veloura.com</a></li>
            <li>Phone: 1-800-VELOURA</li>
        </ul>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 