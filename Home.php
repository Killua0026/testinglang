<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.php");
    exit;
}

// Prevent cached pages after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Digital Products</title>
    <link rel="stylesheet" href="Home.css">
</head>

<body>
    <div class="header">
        <div class="header-title">Digital Products</div>
        <div class="header-actions">
            <span class="welcome-msg">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</span>
            <form action="logout.php" method="post" style="display:inline;">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
    <div class="container">
        <h1>Our Digital Products</h1>
        <div class="products">
            <!-- Product cards remain unchanged -->
            <div class="product-card">
                <img src="https://img.icons8.com/color/96/000000/ebook.png" alt="Ebook" class="product-image">
                <div class="product-title">Ultimate Guide Ebook</div>
                <div class="product-desc">A comprehensive guide to mastering your skills.</div>
                <div class="product-price">$19.99</div>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product-card">
                <img src="https://img.icons8.com/color/96/000000/video-course.png" alt="Video Course"
                    class="product-image">
                <div class="product-title">Video Course</div>
                <div class="product-desc">Step-by-step video lessons from experts.</div>
                <div class="product-price">$49.99</div>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product-card">
                <img src="https://img.icons8.com/color/96/000000/software-box.png" alt="Software" class="product-image">
                <div class="product-title">Productivity Software</div>
                <div class="product-desc">Boost your workflow with our latest app.</div>
                <div class="product-price">$29.99</div>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product-card">
                <img src="https://img.icons8.com/color/96/000000/music.png" alt="Music Pack" class="product-image">
                <div class="product-title">Music Pack</div>
                <div class="product-desc">Royalty-free music for your projects.</div>
                <div class="product-price">$14.99</div>
                <button class="buy-btn">Buy Now</button>
            </div>
            <div class="product-card">
                <img src="https://img.icons8.com/color/96/000000/template.png" alt="Template" class="product-image">
                <div class="product-title">Website Templates</div>
                <div class="product-desc">Modern and responsive templates for any site.</div>
                <div class="product-price">$24.99</div>
                <button class="buy-btn">Buy Now</button>
            </div>
        </div>
    </div>
    <div class="footer">
        <a href="https://facebook.com/" target="_blank" title="Facebook">
            <img src="https://img.icons8.com/color/48/000000/facebook-new.png" alt="Facebook" class="social-icon">
        </a>
        <a href="https://twitter.com/" target="_blank" title="Twitter">
            <img src="https://img.icons8.com/color/48/000000/twitter--v1.png" alt="Twitter" class="social-icon">
        </a>
        <a href="https://instagram.com/" target="_blank" title="Instagram">
            <img src="https://img.icons8.com/color/48/000000/instagram-new.png" alt="Instagram" class="social-icon">
        </a>
        <a href="https://linkedin.com/" target="_blank" title="LinkedIn">
            <img src="https://img.icons8.com/color/48/000000/linkedin.png" alt="LinkedIn" class="social-icon">
        </a>
    </div>
</body>

</html>