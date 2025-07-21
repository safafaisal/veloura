<?php
session_start();

if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include 'db/db.php';
include 'includes/activity_logger.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $userId = $_SESSION['user']['id'];

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, phone, address, city, country, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssd", $userId, $phone, $address, $city, $country, $total);
    $stmt->execute();
    $stmt->close();

    // Log successful checkout
    logCheckout($total);

    $_SESSION['cart'] = [];

    echo "<h2 style='color: green;'>✅ Order placed successfully!</h2>";
    echo "<p><strong>Phone:</strong> $phone</p>";
    echo "<p><strong>Address:</strong> $address</p>";
    echo "<p><strong>City:</strong> $city</p>";
    echo "<p><strong>Country:</strong> $country</p>";
    echo "<p><strong>Total:</strong> Rs. $total</p>";
    echo "<a href='index.php'>Back to Shop</a>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Veloura</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            padding: 40px;
            background-color: #fff0f5;
            color: #333;
        }

        h1 {
            color: #d81b60;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #d81b6044;
            text-align: center;
        }

        .checkout-container {
            max-width: 800px;
            margin: auto;
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .form-section {
            flex: 1;
            min-width: 350px;
            background: #fff;
            padding: 36px 32px 32px 32px;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(216,27,96,0.10);
        }

        .map-section {
            flex: 1;
            min-width: 350px;
            background: #fff;
            padding: 36px 32px 32px 32px;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(216,27,96,0.10);
        }

        .map-container {
            width: 100%;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #f8bbd0;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d81b60;
            font-weight: bold;
        }

        .location-info {
            margin-top: 20px;
            padding: 15px;
            background: #fce4ec;
            border-radius: 8px;
            border-left: 4px solid #d81b60;
        }

        .location-info h4 {
            color: #d81b60;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .location-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        label {
            display: block;
            margin: 18px 0 7px;
            font-weight: bold;
            color: #d81b60;
            font-size: 16px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #f8bbd0;
            border-radius: 8px;
            font-size: 16px;
            background: #fce4ec;
            color: #333;
            transition: border 0.2s;
        }

        input:focus, select:focus {
            border: 1.5px solid #d81b60;
            outline: none;
        }

        .btn {
            background: linear-gradient(90deg, #d81b60 0%, #ffb6c1 100%);
            color: white;
            padding: 12px 28px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(216,27,96,0.10);
            transition: 0.2s;
            width: 100%;
        }

        .btn:hover {
            background: linear-gradient(90deg, #ad1457 0%, #f8bbd0 100%);
            color: #fff;
            transform: scale(1.04);
            box-shadow: 0 4px 16px rgba(216,27,96,0.18);
        }

        .btn-secondary {
            background: #6c757d;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .map-instructions {
            text-align: center;
            color: #d81b60;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .map-placeholder {
            text-align: center;
            padding: 20px;
        }

        .map-placeholder h3 {
            color: #d81b60;
            margin-bottom: 10px;
        }

        .map-placeholder p {
            color: #666;
            margin-bottom: 15px;
        }

        .location-button {
            background: linear-gradient(135deg, #d81b60 0%, #ffb6c1 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .location-button:hover {
            background: linear-gradient(135deg, #ad1457 0%, #f8bbd0 100%);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .checkout-container {
                flex-direction: column;
            }
            
            .form-section, .map-section {
                min-width: auto;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<h1>Checkout</h1>

<div class="checkout-container">
    <div class="form-section">
        <h3 style="color: #d81b60; margin-bottom: 20px;"><i class="fas fa-shipping-fast"></i> Delivery Information</h3>
        
        <form method="POST" id="checkoutForm">
            <label>Phone Number</label>
            <input type="text" name="phone" id="phone" required>

            <label>Address</label>
            <input type="text" name="address" id="address" required>

            <label>City</label>
            <input type="text" name="city" id="city" required>

            <label>Country</label>
            <select name="country" id="country" required>
                <option value="">Select Country</option>
                <option value="Pakistan">Pakistan</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="USA">USA</option>
                <option value="Canada">Canada</option>
                <option value="Other">Other</option>
            </select>

            <button type="submit" class="btn">Place Order</button>
            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
        </form>
    </div>

    <div class="map-section">
        <h3 style="color: #d81b60; margin-bottom: 20px;"><i class="fas fa-map-marker-alt"></i> Delivery Location</h3>
        
        <div class="map-container">
            <div class="map-placeholder">
                <h3><i class="fas fa-map-marked-alt"></i> Location Selection</h3>
                <p>Click the button below to get your current location and automatically fill the address fields.</p>
                <button class="location-button" onclick="getCurrentLocation()">
                    <i class="fas fa-location-arrow"></i> Get My Location
                </button>
            </div>
        </div>
        
        <div class="location-info" id="locationInfo" style="display: none;">
            <h4><i class="fas fa-map-pin"></i> Selected Location</h4>
            <p><strong>Address:</strong> <span id="selectedAddress">-</span></p>
            <p><strong>City:</strong> <span id="selectedCity">-</span></p>
            <p><strong>Country:</strong> <span id="selectedCountry">-</span></p>
            <p><strong>Coordinates:</strong> <span id="selectedCoords">-</span></p>
        </div>
    </div>
</div>

<script>
function getCurrentLocation() {
    if (navigator.geolocation) {
        // Show loading state
        document.getElementById('locationInfo').style.display = 'block';
        document.getElementById('selectedAddress').textContent = 'Getting location...';
        document.getElementById('selectedCity').textContent = 'Getting location...';
        document.getElementById('selectedCountry').textContent = 'Getting location...';
        document.getElementById('selectedCoords').textContent = 'Getting coordinates...';
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                console.log('Location obtained:', lat, lng);
                
                // Show coordinates immediately
                document.getElementById('selectedCoords').textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
                
                // Log location selection
                fetch('log_location.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'MAP_LOCATION',
                        coordinates: lat.toFixed(6) + ', ' + lng.toFixed(6)
                    })
                });
                
                // Make external API call to get address
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1&accept-language=en`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('API Response:', data);
                        
                        if (data && data.address) {
                            const address = data.address;
                            let streetAddress = '';
                            let city = '';
                            let country = '';
                            
                            // Extract address components
                            if (address.house_number && address.road) {
                                streetAddress = address.house_number + ' ' + address.road;
                            } else if (address.road) {
                                streetAddress = address.road;
                            } else if (address.suburb) {
                                streetAddress = address.suburb;
                            } else {
                                streetAddress = 'Location selected';
                            }
                            
                            city = address.city || address.town || address.village || address.county || address.state || '';
                            
                            // Better country detection
                            if (address.country) {
                                country = address.country;
                            } else if (address.country_code) {
                                const countryNames = {
                                    'PK': 'Pakistan',
                                    'US': 'United States',
                                    'GB': 'United Kingdom',
                                    'CA': 'Canada',
                                    'AU': 'Australia',
                                    'IN': 'India',
                                    'CN': 'China',
                                    'JP': 'Japan',
                                    'DE': 'Germany',
                                    'FR': 'France',
                                    'IT': 'Italy',
                                    'ES': 'Spain',
                                    'BR': 'Brazil',
                                    'MX': 'Mexico',
                                    'AR': 'Argentina',
                                    'ZA': 'South Africa',
                                    'EG': 'Egypt',
                                    'NG': 'Nigeria',
                                    'KE': 'Kenya',
                                    'UG': 'Uganda',
                                    'TZ': 'Tanzania',
                                    'GH': 'Ghana',
                                    'ET': 'Ethiopia',
                                    'DZ': 'Algeria',
                                    'MA': 'Morocco',
                                    'TN': 'Tunisia',
                                    'LY': 'Libya',
                                    'SD': 'Sudan',
                                    'SS': 'South Sudan',
                                    'CF': 'Central African Republic',
                                    'TD': 'Chad',
                                    'NE': 'Niger',
                                    'ML': 'Mali',
                                    'BF': 'Burkina Faso',
                                    'SN': 'Senegal',
                                    'GN': 'Guinea',
                                    'SL': 'Sierra Leone',
                                    'LR': 'Liberia',
                                    'CI': 'Ivory Coast',
                                    'GW': 'Guinea-Bissau',
                                    'GM': 'Gambia',
                                    'CV': 'Cape Verde',
                                    'MR': 'Mauritania',
                                    'DJ': 'Djibouti',
                                    'SO': 'Somalia',
                                    'ER': 'Eritrea',
                                    'RW': 'Rwanda',
                                    'BI': 'Burundi',
                                    'MG': 'Madagascar',
                                    'MU': 'Mauritius',
                                    'SC': 'Seychelles',
                                    'KM': 'Comoros',
                                    'YT': 'Mayotte',
                                    'RE': 'Réunion',
                                    'ST': 'São Tomé and Príncipe',
                                    'GQ': 'Equatorial Guinea',
                                    'GA': 'Gabon',
                                    'CG': 'Republic of the Congo',
                                    'CD': 'Democratic Republic of the Congo',
                                    'AO': 'Angola',
                                    'ZM': 'Zambia',
                                    'ZW': 'Zimbabwe',
                                    'BW': 'Botswana',
                                    'NA': 'Namibia',
                                    'LS': 'Lesotho',
                                    'SZ': 'Eswatini'
                                };
                                country = countryNames[address.country_code.toUpperCase()] || address.country_code;
                            }
                            
                            // Update form fields
                            document.getElementById('address').value = streetAddress;
                            document.getElementById('city').value = city;
                            document.getElementById('country').value = country;
                            
                            // Update display
                            document.getElementById('selectedAddress').textContent = streetAddress;
                            document.getElementById('selectedCity').textContent = city;
                            document.getElementById('selectedCountry').textContent = country;
                            
                            console.log('Location updated successfully:', { streetAddress, city, country });
                        } else {
                            // Fallback
                            document.getElementById('address').value = '';
                            document.getElementById('city').value = '';
                            document.getElementById('country').value = '';
                            
                            document.getElementById('selectedAddress').textContent = 'No address data available';
                            document.getElementById('selectedCity').textContent = 'Please enter manually';
                            document.getElementById('selectedCountry').textContent = 'Please enter manually';
                        }
                    })
                    .catch(error => {
                        console.error('API call failed:', error);
                        
                        // Fallback on error
                        document.getElementById('address').value = '';
                        document.getElementById('city').value = '';
                        document.getElementById('country').value = '';
                        
                        document.getElementById('selectedAddress').textContent = 'API failed - enter manually';
                        document.getElementById('selectedCity').textContent = 'API failed - enter manually';
                        document.getElementById('selectedCountry').textContent = 'API failed - enter manually';
                    });
            },
            function(error) {
                console.error('Geolocation failed:', error);
                
                // Show error message
                document.getElementById('locationInfo').style.display = 'block';
                document.getElementById('selectedAddress').textContent = 'Location access denied';
                document.getElementById('selectedCity').textContent = 'Please enter manually';
                document.getElementById('selectedCountry').textContent = 'Please enter manually';
                document.getElementById('selectedCoords').textContent = 'Location not available';
            }
        );
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}
</script>

</body>
</html>
