<?php

namespace ZenithPHP\Core\Controller;

use PDO;

/**
 * Abstract base Controller class to provide core functionalities for all controllers.
 *
 * @package ZenithPHP\Core\Controller
 */
abstract class Controller
{
    /**
     * Database connection instance.
     *
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * Initializes the Controller and sets up the PDO database connection.
     */
    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";", DB_USER, DB_PASS);
    }

    /**
     * Renders a view file and passes data to it.
     *
     * @param string $filename The name of the view file (without the `.php` extension).
     * @param array $data An associative array of data to pass to the view.
     *
     * @return void
     */
    protected function view(string $filename = '', array $data = []): void
    {
        $baseDir = dirname(__DIR__, 3);

        // Extract variables from the data array
        extract($data);

        // Get the content of the view file
        $content = file_get_contents($baseDir . '/View/' . $filename . '.pluto.php');

        // Replace << variable >> with PHP echo statement
        $content = preg_replace('/<<\s*\$(\w+)\s*>>/', '<?php echo htmlspecialchars($$1, ENT_QUOTES, \'UTF-8\'); ?>', $content);

        // Handle @extends directive
        if (preg_match('/@extends\s*\(\s*(\'|")(.+?)(\'|")\s*\)/', $content, $matches)) {
            $layout = trim($matches[2]); // Get layout name without quotes
            $layoutContent = file_get_contents($baseDir . '/View/' . $layout . '.pluto.php');
            $content = str_replace($matches[0], $layoutContent, $content);
        }

        // Replace directives for conditionals
        $content = preg_replace('/@if\s*\(\s*(.+?)\s*\)/', '<?php if($1): ?>', $content);
        $content = str_replace('@else', '<?php else: ?>', $content);
        $content = str_replace('@endif', '<?php endif; ?>', $content);

        // Fix the foreach loop replacement
        $content = preg_replace('/@foreach\s*\(\s*(\$\w+)\s+as\s+(\$\w+)\s*\)/', '<?php foreach ($1 as $2): ?>', $content);
        $content = str_replace('@endforeach', '<?php endforeach; ?>', $content);

        // Implementing @php and @endphp directives
        $content = str_replace('@php', '<?php ', $content);
        $content = str_replace('@endphp', '?>', $content);

        // Handle @section and @yield directives
        if (preg_match_all('/@section\(\s*(\'|")(.+?)(\'|")\s*\)\s*(.*?)@endsection/s', $content, $matches)) {
            foreach ($matches[2] as $index => $sectionName) {
                // Store the section content in a variable
                $this->sections[$sectionName] = $matches[4][$index];
                // Remove the section from the content
                $content = str_replace($matches[0][$index], '', $content);
            }
        }

        // Handle @yield directives
        $content = preg_replace_callback('/@yield\(\s*(\'|")(.+?)(\'|")\s*\)/', function ($matches) {
            return isset($this->sections[$matches[2]]) ? $this->sections[$matches[2]] : '';
        }, $content);

        // Start output buffering
        ob_start();

        // Safely evaluate the content
        eval("?>" . trim($content)); // Use trim() to avoid whitespace issues

        // Get the final output and echo it
        $final = ob_get_clean();
        echo $final;
    }

}
