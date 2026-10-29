<?php
/**
 * Controller quản lý trang Tin tức / Cẩm nang ẩm thực (BlogController)
 */

class BlogController extends BaseController {
    public function index() {
        $blogModel = new BlogModel();
        if (isset($_GET['id'])) {
            $article = $blogModel->getById(is_scalar($_GET['id']) ? (int) $_GET['id'] : 0);
            if (!$article) {
                http_response_code(404);
                $this->render('blog/detail', ['pageTitle' => 'Không tìm thấy bài viết', 'currentNav' => 'blog', 'article' => null]);
                return;
            }
            $this->render('blog/detail', [
                'pageTitle' => $article['title'],
                'currentNav' => 'blog',
                'article' => $article,
            ]);
            return;
        }
        $blogs = $blogModel->getAll();
        $featuredBlog = $blogModel->getFeatured();

        $this->render('blog/index', [
            'pageTitle'    => 'Cẩm Nang Ẩm Thực & Văn Hóa Xứ Huế - O Hương Xứ Huế',
            'currentNav'   => 'blog',
            'blogs'        => $blogs,
            'featuredBlog' => $featuredBlog
        ]);
    }
}
