<?php
/**
 * Autoloader function to automatically load classes when needed.
 * It dynamically includes class files based on their namespace and directory structure.
 */
spl_autoload_register(function ($className) {
    // Define the base directory for the project
    $baseDir = __DIR__ . '/';

    // Replace namespace separators with directory separators in the class name,
    // then add ".php" extension to locate the file
    $file = $baseDir . str_replace('\\', '/', $className) . '.php';

    // Check if the file exists, then require it
    if (file_exists($file)) {
        require $file;
    } else {
        echo "Class file not found: $file\n";  // Debugging statement
    }
});
