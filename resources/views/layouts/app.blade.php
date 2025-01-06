<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VietNews</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .header {
            background-color: #003366;
            color: white;
            padding: 10px 0;
        }

        .header .logo {
            display: flex;
            align-items: center;
        }

        .header .logo img {
            width: 80px;
            margin-right: 10px;
        }

        .header .nav-link {
            color: white;
            margin-right: 15px;
            font-weight: bold;
        }

        .header .nav-link:hover {
            color: #cccccc;
        }

        .footer {
            background-color: #003366;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .footer a {
            color: white;
            margin: 0 10px;
        }

        .footer a:hover {
            color: #cccccc;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <img src="{{ asset('images/logoApp.png') }}" alt="Logo">
                <h1>VietNews</h1>
            </div>
            <nav class="d-flex align-items-center">
                <a href="#" class="nav-link">Chính trị</a>
                <a href="#" class="nav-link">Kinh tế</a>
                <a href="#" class="nav-link">Giáo dục</a>
                <a href="#" class="nav-link">Giải trí</a>
                <a href="#" class="nav-link">Thể thao</a>
                <form class="d-flex align-items-center">
                    <input type="text" placeholder="Search" class="form-control">
                    <button class="btn btn-light ml-2"><i class="fas fa-search"></i></button>
                </form>
                <button class="btn btn-primary ml-3">Log in</button>
                <button class="btn btn-danger ml-2">Log out</button>
            </nav>
        </div>
    </header>

    <main class="container my-4">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container d-flex justify-content-between">
            <div>
                <h5>VietNews</h5>
                <p>Contact: 012345678</p>
                <p>Address: 175 Tay Son Street</p>
                <p>Email: abc@gmail.com</p>
            </div>
            <div>
                <h5>VietNews</h5>
                <p>Contact: 012345678</p>
                <p>Address: 175 Tay Son Street</p>
                <p>Email: abc@gmail.com</p>
            </div>
            <div>
                <h5>VietNews</h5>
                <p>Contact: 012345678</p>
                <p>Address: 175 Tay Son Street</p>
                <p>Email: abc@gmail.com</p>
            </div>
        </div>
        <div class="mt-3">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-pinterest"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
