<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use Nextcloud\CodingStandard\Config;

class MyConfig extends Config
{
	public function getRules(): array
	{
		$rules = parent::getRules();
		$rules['@PhpCsFixer'] = true;
		$rules['curly_braces_position']['classes_opening_brace'] = 'next_line_unless_newline_at_signature_end';
		$rules['phpdoc_separation'] = false;
		$rules['phpdoc_to_comment'] = false;
		$rules['single_line_comment_style'] = false;
		return $rules;
	}
}

$config = new MyConfig();
$config
	->getFinder()
	->ignoreVCSIgnored(true)
	->notPath('build')
	->notPath('l10n')
	->notPath('src')
	->notPath('vendor')
	->in(__DIR__);
return $config;
