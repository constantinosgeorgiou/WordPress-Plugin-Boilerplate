<?php
/**
 * Script to replace @author and @link PHPDoc tags in WordPress plugin files.
 *
 * Usage: php replace_phpdoc.php <plugin_directory> <author_name> <link_url>
 *
 * Example : php replace_phpdoc.php .././plugin-name "Author Name   <Email address>" "https://example.com"
 */

if ( $argc < 4 ) {
	echo "Usage: php replace_phpdoc.php <plugin_directory> <author_name> <link_url>\n";
	exit( 1 );
}

$pluginDir  = $argv[1];
$authorName = $argv[2];
$linkUrl    = $argv[3];

if ( ! is_dir( $pluginDir ) ) {
	echo "Error: Directory '$pluginDir' does not exist.\n";
	exit( 1 );
}

$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator( $pluginDir, RecursiveDirectoryIterator::SKIP_DOTS )
);

foreach ( $iterator as $file ) {
	// Process only PHP files.
	if ( $file->isFile() && strtolower( $file->getExtension() ) === 'php' ) {
		$filePath        = $file->getRealPath();
		$content         = file_get_contents( $filePath );
		$originalContent = $content;

		// Replace @author tag.
		$content = preg_replace(
			'/(@author\s+)[^\n\r]*/',
			'$1' . $authorName,
			$content
		);

		// Replace @link tag.
		$content = preg_replace(
			'/(@link\s+)[^\n\r]*/',
			'$1' . $linkUrl,
			$content
		);

		// If changes are made, write them back to the file
		if ( $content !== $originalContent ) {
			file_put_contents( $filePath, $content );
			echo "Updated file: $filePath\n";
		}
	}
}
