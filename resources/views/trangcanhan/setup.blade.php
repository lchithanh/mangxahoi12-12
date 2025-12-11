<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoàn thiện thông tin cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            font-family: Arial, sans-serif;
        }
        .setup-box {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="setup-box">
    <h3 class="text-center mb-4">Hoàn thiện thông tin cá nhân</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

   <form action="{{ route('trangcanhan.edit') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        @if(isset($user) && $user->anh_dai_dien)
            <img src="{{ asset('storage/' . $user->anh_dai_dien) }}" alt="Avatar" width="120">
        @else
            <img src="https://via.placeholder.com/120" alt="Avatar">
        @endif
    </div>
    <input type="text" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" placeholder="Họ tên" required>
    <input type="file" name="anh_dai_dien">
    <button type="submit">Lưu</button>
</form>
@if($errors->any())
    <ul>
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
@endif

</div>

</body>
</html>
