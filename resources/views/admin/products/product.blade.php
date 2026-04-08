@extends('layouts.app')

@section('content')

<style>
    .product-gallery {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .main-image img {
        width: 450px;
        height: 450px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #eee;
    }

    .thumbnail-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .thumbnail-list img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #eee;
        cursor: pointer;
        transition: border 0.2s;
    }

    .thumbnail-list img:hover,
    .thumbnail-list img.active {
        border: 2px solid #0057b7;
    }
</style>

<h2>Product Name</h2>

<div class="product-gallery">

    <!-- Main Image -->
    <div class="main-image">
        <img id="mainImage" src="https://via.placeholder.com/450" alt="Main Product">
    </div>

    <!-- Thumbnail Images (Right Side) -->
    <div class="thumbnail-list">
        <img src="https://via.placeholder.com/90/FF5733" alt="Image 1" onclick="changeImage(this)" class="active">
        <img src="https://via.placeholder.com/90/33FF57" alt="Image 2" onclick="changeImage(this)">
        <img src="https://via.placeholder.com/90/3357FF" alt="Image 3" onclick="changeImage(this)">
        <img src="https://via.placeholder.com/90/FF33A8" alt="Image 4" onclick="changeImage(this)">
    </div>

</div>

<script>
    function changeImage(thumbnail) {
        // Update main image
        document.getElementById('mainImage').src = thumbnail.src;

        // Remove active from all
        document.querySelectorAll('.thumbnail-list img').forEach(img => {
            img.classList.remove('active');
        });

        // Set active on clicked
        thumbnail.classList.add('active');
    }
</script>

@endsection