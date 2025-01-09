<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - NewsViet</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .header {
            background-color: #003366;
            color: white;
            padding: 10px 0;
        }

        .header .logo img {
            width: 80px;
            margin-right: 10px;
        }

        .header nav a {
            color: white;
            font-weight: bold;
            margin-right: 15px;
            text-decoration: none;
        }

        .header nav a:hover {
            color: #cccccc;
        }

        .article-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .article-card:hover {
            transform: translateY(-5px);
        }

        .article-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .article-card .card-body {
            padding: 15px;
        }

        .article-card h5 {
            font-size: 1.2rem;
            color: #003366;
            margin-bottom: 10px;
        }

        .article-card p {
            color: #555;
            font-size: 0.9rem;
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
                <img src="{{ asset('images/logoApp.jpg') }}" alt="Logo">
                <h1>NewsViet</h1>
            </div>
            <nav>
                <a href="#">Chính trị</a>
                <a href="#">Kinh tế</a>
                <a href="#">Giáo dục</a>
                <a href="#">Giải trí</a>
                <a href="#">Thể thao</a>

                @auth
                <span class="ml-3 text-white">Xin chào, {{ Auth::user()->name }}!</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline-block ml-3">
                    @csrf
                    <button class="btn btn-danger btn-sm">Đăng xuất</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm ml-3">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-success btn-sm ml-2">Đăng ký</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container my-4">
        <div class="row">
            @foreach($articles as $article)
            <div class="col-md-4 mb-4">
                <div class="article-card">
                    <img src="{{ asset('images/' . $article->image) }}" alt="{{ $article->title }}">
                    <div class="card-body">
                        <h5>{{ $article->title }}</h5>
                        <p>{{ Str::limit($article->content, 100) }}</p>
                        <a href="{{ route('reader.articles.show', $article->id) }}" class="btn btn-primary btn-sm">Xem thêm</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    <footer class="footer">
        <div>
            <h5>NewsViet</h5>
            <p>Contact: 012345678</p>
            <p>Address: 175 Tay Son Street</p>
            <p>Email: abc@gmail.com</p>
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
