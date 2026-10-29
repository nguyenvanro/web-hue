<?php
/**
 * View Trang Blog O Hương Xứ Huế
 * Bao gồm 2 chuyên mục: Góc Ẩm Thực & Góc Du Lịch
 * Bài viết ẩm thực và du lịch Huế
 */
$theme_uri     = function_exists('get_template_directory_uri') ? get_template_directory_uri() : '.';
$home_url      = function_exists('home_url') ? home_url('/') : 'index.php';
$blogs         = $blogs ?? [];
$featuredBlog  = $featuredBlog ?? ($blogs[0] ?? null);
$categoryParam = $_GET['cat'] ?? 'all';
$blogCategory = is_string($categoryParam) ? str_replace('goc-', '', $categoryParam) : 'all';
if (!in_array($blogCategory, ['am-thuc', 'du-lich'], true)) $blogCategory = 'all';
$blogBanners = [
    'all' => ['tag' => '📰 Cẩm Nang Khám Phá Cố Đô', 'title' => 'Blog Cố Đô: Ẩm Thực & Du Lịch Xứ Huế', 'description' => 'Khám phá văn hóa, hành trình du lịch và nghệ thuật thưởng thức tinh hoa xứ Cố Đô.'],
    'am-thuc' => ['tag' => '🍲 Tinh Hoa Ẩm Thực Xứ Huế', 'title' => 'Góc Ẩm Thực: Hương Vị Xứ Huế', 'description' => 'Khám phá món ngon, cách thưởng thức và câu chuyện phía sau những đặc sản làm nên hương vị Huế.'],
    'du-lich' => ['tag' => '🏯 Hành Trình Khám Phá Cố Đô', 'title' => 'Góc Du Lịch: Khám Phá Xứ Huế', 'description' => 'Dạo bước qua di sản, khám phá cảnh sắc và kết nối mỗi hành trình với những trải nghiệm ẩm thực địa phương.'],
];
$blogBanner = $blogBanners[$blogCategory];
$blogSections = [
    'all' => ['title' => 'Khám Phá Tất Cả Bài Viết', 'description' => 'Gồm cả Góc Ẩm Thực và Góc Du Lịch Xứ Huế'],
    'am-thuc' => ['title' => 'Bài viết ẩm thực', 'description' => 'Khám phá món ngon, cách thưởng thức và đặc sản Huế.'],
    'du-lich' => ['title' => 'Bài viết du lịch', 'description' => 'Khám phá điểm đến, di sản và trải nghiệm du lịch Huế.'],
];
$blogSection = $blogSections[$blogCategory];
$categoryBlogs = array_values(array_filter($blogs, function ($blog) use ($blogCategory) {
    return $blogCategory === 'all' || str_replace('goc-', '', $blog['category_slug'] ?? '') === $blogCategory;
}));
$featuredBlog = $categoryBlogs[0] ?? null;
foreach ($categoryBlogs as $blog) {
    if (!empty($blog['featured'])) { $featuredBlog = $blog; break; }
}
?>

<div class="blog-page-master">
    <!-- Header Banner -->
    <section class="blog-hero-banner" data-blog-category="<?php echo esc_attr($blogCategory); ?>">
        <div class="blog-hero-content">
            <nav class="blog-breadcrumb">
                <a href="<?php echo esc_url($home_url); ?>">Trang Chủ</a> &rsaquo; <span>Blog Cố Đô</span>
            </nav>
            <span class="blog-hero-tag"><?php echo htmlspecialchars($blogBanner['tag']); ?></span>
            <h1 class="blog-hero-title"><?php echo htmlspecialchars($blogBanner['title']); ?></h1>
            <p class="blog-hero-desc">
                <?php echo htmlspecialchars($blogBanner['description']); ?>
            </p>

            <!-- BỘ LỌC CHUYÊN MỤC BLOG: GÓC ẨM THỰC & GÓC DU LỊCH -->
            <div class="blog-tab-filter" style="margin-top: 25px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <button type="button" class="btn-blog-tab active" onclick="filterBlogMvc('all', this)" style="padding: 10px 22px; border-radius: 25px; font-weight: 700; font-size: 14px; border: 2px solid #d4af37; cursor: pointer; background: #d4af37; color: #4A0E17;">
                    ✨ Tất Cả Bài Viết
                </button>
                <button type="button" class="btn-blog-tab" onclick="filterBlogMvc('am-thuc', this)" style="padding: 10px 22px; border-radius: 25px; font-weight: 700; font-size: 14px; border: 2px solid #d4af37; cursor: pointer; background: #ffffff; color: #4A0E17;">
                    🍲 Góc Ẩm Thực
                </button>
                <button type="button" class="btn-blog-tab" onclick="filterBlogMvc('du-lich', this)" style="padding: 10px 22px; border-radius: 25px; font-weight: 700; font-size: 14px; border: 2px solid #d4af37; cursor: pointer; background: #ffffff; color: #4A0E17;">
                    🏯 Góc Du Lịch Cố Đô
                </button>
            </div>
        </div>
    </section>

    <div class="blog-container">
        <!-- BÀI VIẾT NỔI BẬT (FEATURED HERO) -->
        <div id="blogFeaturedWrap">
            <?php if ($featuredBlog): ?>
                <div class="featured-blog-card" data-url="<?php echo esc_url(BlogModel::articleUrl($featuredBlog['id'])); ?>" role="link" tabindex="0" onclick="window.location.assign(this.dataset.url)" onkeydown="if (event.key === 'Enter' && event.target === this) window.location.assign(this.dataset.url)">
                    <div class="featured-img-col">
                        <img src="<?php echo htmlspecialchars($featuredBlog['image']); ?>" alt="<?php echo htmlspecialchars($featuredBlog['title']); ?>">
                        <span class="featured-tag">★ Bài Viết Nổi Bật</span>
                    </div>
                    <div class="featured-info-col">
                        <div class="blog-meta-row">
                            <span class="meta-category"><?php echo htmlspecialchars($featuredBlog['category']); ?></span>
                            <span class="meta-dot">&bull;</span>
                            <span class="meta-date">📅 <?php echo htmlspecialchars($featuredBlog['date']); ?></span>
                            <span class="meta-dot">&bull;</span>
                            <span class="meta-read">⏳ <?php echo htmlspecialchars($featuredBlog['read_time']); ?></span>
                        </div>

                        <h2 class="featured-title"><a href="<?php echo esc_url(BlogModel::articleUrl($featuredBlog['id'])); ?>" onclick="event.stopPropagation()"><?php echo htmlspecialchars($featuredBlog['title']); ?></a></h2>
                        <p class="featured-summary"><?php echo htmlspecialchars($featuredBlog['summary']); ?></p>

                        <div class="featured-author-row">
                            <div class="author-avatar">✍️</div>
                            <div class="author-info">
                                <strong><?php echo htmlspecialchars($featuredBlog['author']); ?></strong>
                                <span>Chuyên gia Khám phá Văn hóa Huế</span>
                            </div>
                            <button type="button" class="btn-read-featured">
                                Đọc Toàn Bộ Bài Viết &rarr;
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- DANH SÁCH CÁC BÀI VIẾT MỚI NHẤT -->
        <div class="blog-list-header">
            <h2 class="blog-section-title" id="mvcBlogTitle"><?php echo htmlspecialchars($blogSection['title']); ?></h2>
            <p class="blog-section-sub" id="mvcBlogSub"><?php echo htmlspecialchars($blogSection['description']); ?></p>
        </div>

        <div class="blog-grid" id="mvcBlogGrid">
            <?php foreach ($blogs as $b): 
                $bCatSlug = ($b['category_slug'] ?? '') ?: (($b['category'] === 'Góc Du Lịch') ? 'du-lich' : 'am-thuc');
                $showCard = $blogCategory === 'all' || str_replace('goc-', '', $bCatSlug) === $blogCategory;
            ?>
                <article class="blog-card" data-cat="<?php echo esc_attr($bCatSlug); ?>" data-url="<?php echo esc_url(BlogModel::articleUrl($b['id'])); ?>" role="link" tabindex="0" style="<?php echo $showCard ? '' : 'display: none;'; ?>" onclick="window.location.assign(this.dataset.url)" onkeydown="if (event.key === 'Enter' && event.target === this) window.location.assign(this.dataset.url)">
                    <div class="blog-card-img">
                        <img src="<?php echo htmlspecialchars($b['image']); ?>" alt="<?php echo htmlspecialchars($b['title']); ?>">
                        <span class="blog-category-badge"><?php echo htmlspecialchars($b['category']); ?></span>
                    </div>

                    <div class="blog-card-body">
                        <div class="blog-card-meta">
                            <span>📅 <?php echo htmlspecialchars($b['date']); ?></span>
                            <span>⏳ <?php echo htmlspecialchars($b['read_time']); ?></span>
                        </div>

                        <h3 class="blog-card-title"><a href="<?php echo esc_url(BlogModel::articleUrl($b['id'])); ?>" onclick="event.stopPropagation()"><?php echo htmlspecialchars($b['title']); ?></a></h3>
                        <p class="blog-card-excerpt"><?php echo htmlspecialchars($b['summary']); ?></p>

                        <div class="blog-card-footer">
                            <span class="author-name">✍️ <?php echo htmlspecialchars($b['author']); ?></span>
                            <span class="read-more-link">Đọc tiếp &rarr;</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- KHỐI THÔNG BÁO KHI CHUYÊN MỤC ĐANG CẬP NHẬT -->
        <div id="mvcBlogEmptyNotice" style="display: none; text-align: center; padding: 50px 20px; background: #fff; border: 2px dashed #d4af37; border-radius: 16px; margin: 30px 0;">
            <div style="font-size: 48px; margin-bottom: 12px;">🍲</div>
            <h3 style="color: #7A1E2E; font-size: 20px; margin-bottom: 8px;">Chưa có bài viết trong chuyên mục này</h3>
            <p style="color: #666; max-width: 500px; margin: 0 auto; line-height: 1.6;">
                Bạn có thể chọn chuyên mục khác để đọc thêm những câu chuyện về ẩm thực và du lịch Huế.
            </p>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL ĐỌC BÀI VIẾT TỨC THÌ (BLOG READER MODAL) -->
<!-- ======================================================== -->
<div id="blogReadModal" class="quick-modal-overlay" onclick="closeBlogModal(event)">
    <div class="quick-modal-content blog-modal-box" onclick="event.stopPropagation()">
        <button type="button" class="quick-modal-close" onclick="closeBlogModal(null)">&times;</button>
        
        <div class="blog-modal-header">
            <div class="modal-category" id="modalBlogCat">Góc Du Lịch</div>
            <h2 id="modalBlogTitle">Tiêu Đề Bài Viết</h2>
            <div class="modal-author-bar">
                <span id="modalBlogAuthor">✍️ Tác giả</span>
                <span id="modalBlogDate">📅 16/09/2026</span>
                <span id="modalBlogReadTime">⏳ 5 phút</span>
            </div>
        </div>

        <div class="blog-modal-body">
            <div class="blog-modal-cover">
                <img id="modalBlogImg" src="" alt="Ảnh bài viết">
            </div>
            <p id="modalBlogSummary" class="blog-modal-summary"></p>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            <div id="modalBlogContent" class="blog-modal-full-content"></div>
        </div>

        <div class="blog-modal-footer">
            <a id="modalBlogArticleLink" class="read-more-link" href="#">Mở trang bài viết riêng &rarr;</a>
            <button type="button" class="btn-close-modal-bottom" onclick="closeBlogModal(null)">Đóng Cửa Sổ</button>
        </div>
    </div>
</div>

<script>
const mvcBlogsData = <?php echo json_encode($blogs, JSON_UNESCAPED_UNICODE); ?>;
const blogBanners = <?php echo json_encode($blogBanners, JSON_UNESCAPED_UNICODE); ?>;
const blogSections = <?php echo json_encode($blogSections, JSON_UNESCAPED_UNICODE); ?>;

function updateBlogBanner(category) {
    const banner = document.querySelector('.blog-hero-banner');
    const content = blogBanners[category] || blogBanners.all;
    banner.dataset.blogCategory = category;
    banner.querySelector('.blog-hero-tag').textContent = content.tag;
    banner.querySelector('.blog-hero-title').textContent = content.title;
    banner.querySelector('.blog-hero-desc').textContent = content.description;
}

function filterBlogMvc(cat, btn) {
    updateBlogBanner(cat);
    document.querySelectorAll('.btn-blog-tab').forEach(b => {
        b.style.background = '#ffffff';
        b.style.color = '#4A0E17';
    });
    if (btn) {
        btn.style.background = '#d4af37';
        btn.style.color = '#4A0E17';
    }

    const cards = document.querySelectorAll('#mvcBlogGrid .blog-card');
    const featuredWrap = document.getElementById('blogFeaturedWrap');
    const emptyNotice = document.getElementById('mvcBlogEmptyNotice');
    const title = document.getElementById('mvcBlogTitle');
    const sub = document.getElementById('mvcBlogSub');
    const section = blogSections[cat] || blogSections.all;
    title.hidden = false;
    title.textContent = section.title;
    sub.textContent = section.description;
    const filtered = mvcBlogsData.filter(blog => cat === 'all' || (blog.category_slug || '').replace(/^goc-/, '') === cat);
    const featured = filtered.find(blog => blog.featured) || filtered[0];
    const featuredCard = featuredWrap.querySelector('.featured-blog-card');
    if (featured && featuredCard) {
        const image = featuredCard.querySelector('img');
        image.src = featured.image;
        image.alt = featured.title;
        featuredCard.querySelector('.meta-category').textContent = featured.category;
        featuredCard.querySelector('.meta-date').textContent = '📅 ' + featured.date;
        featuredCard.querySelector('.meta-read').textContent = '⏳ ' + featured.read_time;
        const featuredTitleLink = featuredCard.querySelector('.featured-title a');
        featuredTitleLink.textContent = featured.title;
        featuredTitleLink.href = featured.url;
        featuredCard.querySelector('.featured-summary').textContent = featured.summary;
        featuredCard.querySelector('.author-info strong').textContent = featured.author;
        featuredCard.dataset.url = featured.url;
        featuredCard.onclick = () => window.location.assign(featured.url);
    }
    featuredWrap.hidden = !featured;

    if (cat === 'am-thuc') {
        let count = 0;
        cards.forEach(c => {
            const match = ['am-thuc', 'goc-am-thuc'].includes(c.getAttribute('data-cat'));
            c.style.display = match ? '' : 'none';
            if (match) count++;
        });
        emptyNotice.style.display = count === 0 ? 'block' : 'none';
    } else if (cat === 'du-lich') {
        let count = 0;
        cards.forEach(c => {
            if (['du-lich', 'goc-du-lich'].includes(c.getAttribute('data-cat'))) {
                c.style.display = '';
                count++;
            } else {
                c.style.display = 'none';
            }
        });
        emptyNotice.style.display = count === 0 ? 'block' : 'none';
    } else {
        cards.forEach(c => c.style.display = '');
        emptyNotice.style.display = 'none';
    }
}

function openBlogModal(id) {
    const blog = mvcBlogsData.find(b => b.id === id);
    if (!blog) return;
    document.getElementById('modalBlogCat').textContent = blog.category;
    document.getElementById('modalBlogTitle').textContent = blog.title;
    document.getElementById('modalBlogAuthor').textContent = '✍️ ' + blog.author;
    document.getElementById('modalBlogDate').textContent = '📅 ' + blog.date;
    document.getElementById('modalBlogReadTime').textContent = '⏳ ' + blog.read_time;
    document.getElementById('modalBlogImg').src = blog.image;
    document.getElementById('modalBlogSummary').textContent = blog.summary;
    document.getElementById('modalBlogArticleLink').href = blog.url;
    document.getElementById('modalBlogContent').innerHTML = `<div style="font-size: 15px; line-height: 1.8; color: #333;">${blog.content}</div>`;

    const modal = document.getElementById('blogReadModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeBlogModal(event) {
    if (!event || event.target.id === 'blogReadModal' || event.target.classList.contains('quick-modal-close') || event.target.classList.contains('btn-close-modal-bottom')) {
        const modal = document.getElementById('blogReadModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const category = new URLSearchParams(window.location.search).get('cat') || document.querySelector('.blog-hero-banner').dataset.blogCategory;
    const buttons = document.querySelectorAll('.btn-blog-tab');
    if (category === 'du-lich' || category === 'goc-du-lich') filterBlogMvc('du-lich', buttons[2]);
    else if (category === 'am-thuc' || category === 'goc-am-thuc') filterBlogMvc('am-thuc', buttons[1]);
    else filterBlogMvc('all', buttons[0]);
});
</script>
