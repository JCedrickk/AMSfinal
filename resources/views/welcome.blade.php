{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Connect - Stay Connected</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .feature-card {
            transition: transform 0.3s;
            margin-bottom: 30px;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .stats-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        .stat-number {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
        }
        footer {
            background-color: #2d3748;
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Welcome to Alumni Connect</h1>
            <p class="lead mb-4">Stay connected with your alma mater and fellow graduates</p>
            @if (Route::has('login'))
                <div class="d-flex justify-content-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Register</a>
                    @endauth
                </div>
            @endif
        </div>
    </div>

    <!-- Features Section -->
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Connect with Alumni</h5>
                        <p class="card-text">Find and connect with fellow graduates from your batch and course.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <i class="fas fa-newspaper fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Share Updates</h5>
                        <p class="card-text">Post updates, achievements, and stay informed about alumni events.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Career Opportunities</h5>
                        <p class="card-text">Discover job opportunities and professional networking.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-number" id="alumniCount">0</div>
                    <p>Alumni Members</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number" id="postCount">0</div>
                    <p>Total Posts</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number" id="countriesCount">0+</div>
                    <p>Countries</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number" id="eventsCount">0</div>
                    <p>Events Yearly</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="container my-5">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <h3 class="mb-0">1</h3>
                </div>
                <h5>Register</h5>
                <p>Create your account and verify your alumni status</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <h3 class="mb-0">2</h3>
                </div>
                <h5>Get Approved</h5>
                <p>Wait for admin approval to activate your account</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <h3 class="mb-0">3</h3>
                </div>
                <h5>Connect</h5>
                <p>Start posting, commenting, and connecting with alumni</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="container text-center mb-5">
        <div class="card bg-primary text-white">
            <div class="card-body py-5">
                <h3 class="mb-3">Ready to reconnect?</h3>
                <p class="mb-4">Join our growing community of alumni today!</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Register Now</a>
                @endguest
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Alumni Connect</h5>
                    <p>Keeping alumni connected with their alma mater and each other.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Alumni Connect. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animate stats counting
        function animateCounter(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                element.innerText = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Fetch real stats from API
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                animateCounter(document.getElementById('alumniCount'), 0, data.alumni, 2000);
                animateCounter(document.getElementById('postCount'), 0, data.posts, 2000);
                document.getElementById('countriesCount').innerText = data.countries + '+';
                animateCounter(document.getElementById('eventsCount'), 0, data.events, 2000);
            })
            .catch(error => console.error('Error fetching stats:', error));
    </script>
</body>
</html>