<?php
$dir = __DIR__ . '/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$count = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        $original = $content;
        
        // Single regex to match the wrapper AND the heading, and replace with new wrapper WITHOUT heading
        $content = preg_replace(
            '/(<div class="d-flex\s+)justify-content-between([^"]*?)\s*pt-3\s+pb-2\s+mb-3\s+border-bottom(".*?>)\s*<h[1-6][^>]*>.*?<\/h[1-6]>\s*/is',
            '$1justify-content-end$2 pb-2 mb-3$3 ',
            $content
        );
        
        if ($original !== $content) {
            file_put_contents($path, $content);
            echo "Updated " . htmlspecialchars($file->getRealPath()) . "<br>\n";
            $count++;
        }
    }
}
echo "Total updated: $count\n";
?>
