<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - NewsViet</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
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

        .article-title {
            font-size: 2rem;
            font-weight: bold;
            color: #003366;
            margin-top: 20px;
        }

        .article-meta {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
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

        .no-decoration {
            text-decoration: none;
            /* Loại bỏ gạch chân */
            color: inherit;
            /* Giữ màu sắc văn bản của phần tử cha (không đổi màu) */
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <img src="{{ asset('images/logoApp.jpg') }}" alt="Logo">
                <h2><a href="{{ route('home') }}" class="no-decoration">NewsViet</a></h2>

            </div>
            <nav>
                <a href="#">Chính trị</a>
                <a href="#">Kinh tế</a>
                <a href="#">Giáo dục</a>
                <a href="#">Giải trí</a>
                <a href="#">Thể thao</a>

                @auth
                <!-- Nếu đã đăng nhập -->
                <span class="ml-3 text-white">Xin chào, {{ Auth::user()->name }}!</span>
                <form action="{{ route('logout') }}" method="POST" class="ml-3">
                    @csrf
                    <button class="btn btn-danger">Đăng xuất</button>
                </form>
                @else
                <!-- Nếu chưa đăng nhập -->
                <a href="{{ route('login') }}" class="btn btn-primary ml-3">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-success ml-2">Đăng ký</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container my-4">
        <article>
            <h1 class="article-title">{{ $article->title }}</h1>
            <p class="article-meta">
                <strong>Tác giả:</strong> {{ $article->author->name ?? 'N/A' }} |
                <strong>Ngày đăng:</strong> {{ $article->created_at ? $article->created_at->format('d/m/Y') : 'N/A' }}
            </p>
            <div class="article-content">
                @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                @endif
                <p>{{ $article->content }}</p>
            </div>
        </article>

        <div class="comments mt-5">
            <h3>Bình luận</h3>

            <!-- Hiển thị danh sách bình luận trong các card -->
            @foreach($article->comments as $comment)
            <div class="card mb-3">
                <div class="card-header">
                    <strong>{{ $comment->user->name }}</strong> <small>({{ $comment->created_at ? $comment->created_at->format('d/m/Y') : 'N/A' }})</small>
                </div>
                <div class="card-body">
                    <p>{{ $comment->content }}</p>

                    <!-- Hiển thị các hành động (sửa, xóa) nếu người dùng là chủ của bình luận -->
                    @auth
                    @if(Auth::id() == $comment->user_id)
                    <div class="mt-3">
                        <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                    @endif
                    @endauth
                </div>
            </div>
            @endforeach

            <!-- Form thêm bình luận -->
            @auth
            <form action="{{ route('comments.store', ['articleId' => $article->id]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="content">Thêm bình luận:</label>
                    <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Gửi</button>
            </form>
            @else
            <p>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</p>
            @endauth
        </div>
    </main>



    <footer class="footer">
        <div>
            <h5>VietNews</h5>
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