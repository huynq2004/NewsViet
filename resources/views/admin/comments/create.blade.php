<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm bình luận</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Thêm bình luận</h1>
    <form action="{{ route('admin.comments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="article_id">Bài viết</label>
            <select name="article_id" id="article_id" class="form-control">
                @foreach ($articles as $article)
                    <option value="{{ $article->id }}">{{ $article->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" id="content" class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
