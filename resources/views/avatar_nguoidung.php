@php
    $avatarPath = $nguoiDung->anh_dai_dien ?? 'default.png';
    $size = $size ?? 40;
@endphp

<img src="{{ asset('storage/avatar/' . $avatarPath) }}"
     class="rounded-circle"
     width="{{ $size }}"
     height="{{ $size }}"
     style="object-fit:cover;">
