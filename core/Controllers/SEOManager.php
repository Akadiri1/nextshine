<?php
    
class SEOManager {
    private $site_name;
    private $metaTitle;
    private $metaDescription;
    private $keywords;
    private $author;
    private $descriptionLimit;
    private $canonical;
    private $robots;
    private $metaImage;
    private $favicon;
    private $charset;

    public function __construct() {
        $this->site_name = APP_NAME;
        $this->metaTitle = APP_NAME;
        $this->metaDescription = APP_NAME;
        $this->keywords = ["Website", "Business", "Mckodev Tech Lab"];
        $this->author = "";
        $this->descriptionLimit = 160;
        $this->canonical = "";
        $this->robots = "";
        $this->metaImage = "/favicon.png";
        $this->favicon = "/favicon.png";
        $this->charset = "UTF-8";
    }

    public function setTitle($title) {
        $this->metaTitle = $title;
    }

    public function setDescription($description) {
        $this->metaDescription = $description;
    }

    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function setFavicon($favicon) {
        $this->favicon = $favicon;
    }

    public function setMetaImage($image) {
        $this->metaImage = $image;
    }

    public function setCanonical($canonical) {
        $this->canonical = $canonical;
    }

    public function render() {
        $meta_image = $this->metaImage;
        $favicon = $this->favicon;
        $description = $this->metaDescription;
        $keywords = is_array($this->keywords) ? implode(",", $this->keywords) : $this->keywords;
        $canonical = $this->canonical ?: "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Ensure meta image and favicon are full URLs
        if (!preg_match("/^https?:\/\//", $meta_image)) {
            $meta_image = "https://" . $_SERVER['HTTP_HOST'] . '/' . ltrim($meta_image, '/');
        }
        if (!preg_match("/^https?:\/\//", $favicon)) {
            $favicon = "https://" . $_SERVER['HTTP_HOST'] . '/' . ltrim($favicon, '/');
        }

        // Trim description if it exceeds the limit
        if (strlen($description) > $this->descriptionLimit) {
            $description = substr($description, 0, $this->descriptionLimit) . '...';
        }

        // Output meta tags
        echo "<meta charset=\"{$this->charset}\">\n";
        echo "<meta name=\"description\" content=\"" . htmlspecialchars($description) . "\">\n";
        echo "<meta name=\"keywords\" content=\"" . htmlspecialchars($keywords) . "\">\n";
        echo "<meta name=\"author\" content=\"" . htmlspecialchars($this->author) . "\">\n";
        echo "<meta name=\"robots\" content=\"" . htmlspecialchars($this->robots) . "\">\n";
        echo "<link rel=\"canonical\" href=\"" . htmlspecialchars($canonical) . "\">\n";

        // Open Graph tags
        echo "<meta property=\"og:title\" content=\"" . htmlspecialchars($this->metaTitle) . "\">\n";
        echo "<meta property=\"og:description\" content=\"" . htmlspecialchars($description) . "\">\n";
        echo "<meta property=\"og:url\" content=\"" . htmlspecialchars($canonical) . "\">\n";
        echo "<meta property=\"og:type\" content=\"website\">\n";
        echo "<meta property=\"og:image\" content=\"" . htmlspecialchars($meta_image) . "\">\n";

        // Twitter Card tags
        echo "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
        echo "<meta name=\"twitter:title\" content=\"" . htmlspecialchars($this->metaTitle) . "\">\n";
        echo "<meta name=\"twitter:description\" content=\"" . htmlspecialchars($description) . "\">\n";
        echo "<meta name=\"twitter:image\" content=\"" . htmlspecialchars($meta_image) . "\">\n";

        // Title tag
        echo "<title>" . htmlspecialchars($this->metaTitle) . "</title>\n";

        // Favicon
        echo "<link rel=\"shortcut icon\" href=\"" . htmlspecialchars($favicon) . "\">\n";
    }
}
