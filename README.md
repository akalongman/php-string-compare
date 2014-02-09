php-string-compare
==================

This class compares two strings and outputs the similarities/difference as percentage

Usage:
```php
	$string1 = 'This is my test string';
	$string2 = 'This is my second text string';
	require('string_compare.inc.php');
	$phpStringCompare = new StringCompare($string1, $string2,
									array('remove_html_tags'=>false, 'remove_extra_spaces'=>true,
									'remove_punctuation'=>true, 'punctuation_symbols'=>Array('.', ','))
								);
	$percent = $phpStringCompare->getSimilarityPercentage();
	$percent2 = $phpStringCompare->getDifferencePercentage();
	echo $string1.' and '.$string2 are '.$percent.'% similar and '.$percent2.'% differnt';
```