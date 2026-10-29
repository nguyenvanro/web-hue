<?php
$category = $article ? str_replace('goc-', '', $article['category_slug']) : 'all';
$blogUrl = function_exists('home_url') ? home_url('/?controller=blog&cat=' . $category) : 'index.php?controller=blog&cat=' . $category;
?>
<main class="blog-page-master">
    <section class="blog-hero-banner" data-blog-category="<?php echo esc_attr($category); ?>">
        <div class="blog-hero-content">
            <nav class="blog-breadcrumb" aria-label="Điều hướng bài viết">
                <a href="<?php echo esc_url($blogUrl); ?>"><?php echo htmlspecialchars($article['category'] ?? 'Blog'); ?></a>
                &rsaquo; <span>Bài viết chi tiết</span>
            </nav>
            <h1 class="blog-hero-title"><?php echo htmlspecialchars($article['title'] ?? 'Không tìm thấy bài viết'); ?></h1>
            <?php if ($article): ?>
                <p class="blog-hero-desc"><?php echo htmlspecialchars($article['summary']); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <div class="blog-container">
        <?php if ($article): ?>
            <article class="blog-article-detail">
                <div class="blog-card-meta">
                    <span>Biên soạn: <?php echo htmlspecialchars($article['author']); ?></span>
                    <span>Đăng: <?php echo htmlspecialchars($article['date']); ?></span>
                    <span>Cập nhật: <?php echo htmlspecialchars(date('d/m/Y', strtotime($article['modified']))); ?></span>
                    <span><?php echo htmlspecialchars($article['read_time']); ?></span>
                </div>
                <img class="blog-article-cover" src="<?php echo esc_url($article['image']); ?>" alt="<?php echo esc_attr($article['title']); ?>">
                <div class="blog-modal-full-content"><?php echo $article['content']; ?></div>
                <a class="read-more-link" href="<?php echo esc_url($blogUrl); ?>">&larr; Quay lại <?php echo htmlspecialchars($article['category']); ?></a>
            </article>
        <?php else: ?>
            <div class="blog-article-detail">
                <p>Bài viết không tồn tại hoặc đã được chuyển. Bạn có thể quay lại danh sách để chọn bài khác.</p>
                <a href="<?php echo esc_url($blogUrl); ?>">Quay lại Blog</a>
            </div>
        <?php endif; ?>
    </div>
</main>
