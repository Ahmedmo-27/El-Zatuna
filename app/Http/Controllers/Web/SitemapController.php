<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Webinar;
use App\User;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemaps = [
            [
                'loc' => url('/sitemaps/main.xml'),
                'lastmod' => date('c'),
            ],
            [
                'loc' => url('/sitemaps/courses.xml'),
                'lastmod' => date('c'),
            ],
            [
                'loc' => url('/sitemaps/blog.xml'),
                'lastmod' => date('c'),
            ],
            [
                'loc' => url('/sitemaps/products.xml'),
                'lastmod' => date('c'),
            ],
            [
                'loc' => url('/sitemaps/teachers.xml'),
                'lastmod' => date('c'),
            ],
        ];

        return response()
            ->view('sitemap.sitemap_index', ['sitemaps' => $sitemaps])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function main()
    {
        $urls = [];

        $addUrl = function (string $loc, $lastMod = null, string $changefreq = 'weekly', string $priority = '0.6') use (&$urls) {
            $lastmod = null;

            if (!empty($lastMod)) {
                if (is_numeric($lastMod)) {
                    $lastmod = date('c', (int)$lastMod);
                } else {
                    $timestamp = strtotime((string)$lastMod);
                    if ($timestamp !== false) {
                        $lastmod = date('c', $timestamp);
                    }
                }
            }

            $urls[] = [
                'loc' => $loc,
                'lastmod' => $lastmod,
                'changefreq' => $changefreq,
                'priority' => $priority,
            ];
        };

        $addUrl(url('/'), time(), 'daily', '1.0');

        $staticPages = [
            '/classes',
            '/blog',
            '/instructors',
            '/organizations',
            '/products',
            '/about',
            '/terms',
            '/privacy',
            '/cookie',
            '/faq',
            '/contact',
        ];

        foreach ($staticPages as $path) {
            $addUrl(url($path), time(), 'weekly', '0.7');
        }

        return response()
            ->view('sitemap.index', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function courses()
    {
        $urls = [];

        $courses = Webinar::query()
            ->where('status', Webinar::$active)
            ->where('private', false)
            ->select('slug', 'updated_at', 'created_at')
            ->get();

        foreach ($courses as $course) {
            $urls[] = [
                'loc' => url('/course/' . $course->slug),
                'lastmod' => $this->formatLastmod($course->updated_at ?? $course->created_at),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        return response()
            ->view('sitemap.index', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function blog()
    {
        $urls = [];

        $posts = Blog::query()
            ->where('status', 'publish')
            ->select('slug', 'updated_at', 'created_at')
            ->get();

        foreach ($posts as $post) {
            $urls[] = [
                'loc' => url('/blog/' . $post->slug),
                'lastmod' => $this->formatLastmod($post->updated_at ?? $post->created_at),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return response()
            ->view('sitemap.index', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function products()
    {
        $urls = [];

        $products = Product::query()
            ->where('status', Product::$active)
            ->where('ordering', true)
            ->select('slug', 'updated_at', 'created_at')
            ->get();

        foreach ($products as $product) {
            $urls[] = [
                'loc' => url('/products/' . $product->slug),
                'lastmod' => $this->formatLastmod($product->updated_at ?? $product->created_at),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return response()
            ->view('sitemap.index', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function teachers()
    {
        $urls = [];

        $teachers = User::query()
            ->where('role_name', 'teacher')
            ->where('status', 'active')
            ->select('username', 'updated_at', 'created_at')
            ->get();

        foreach ($teachers as $teacher) {
            $urls[] = [
                'loc' => url('/users/' . $teacher->username . '/profile'),
                'lastmod' => $this->formatLastmod($teacher->updated_at ?? $teacher->created_at),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        return response()
            ->view('sitemap.index', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function formatLastmod($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return date('c', (int)$value);
        }

        $timestamp = strtotime((string)$value);

        return $timestamp !== false ? date('c', $timestamp) : null;
    }
}
