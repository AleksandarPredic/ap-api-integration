<?php
/**
 * A tool to automatically fix PHP Coding Standards issues
 *
 * @link https://github.com/FriendsOfPHP/PHP-CS-Fixer
 * @link https://cs.symfony.com
 */
$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('node_modules')
    ->exclude('build')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setCacheFile(__DIR__.'/.php_cs.cache')
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
          'default' => 'single_space',
          'operators' => [
              '=>' => 'single_space',
              '=' => 'single_space',
          ]
        ],
        'align_multiline_comment' => [
            'comment_type' => 'all_multiline',
        ],
        'no_useless_return' => true,
        'not_operator_with_space' => false,
        'is_null' => false,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'yoda_style' => null,
        'phpdoc_order' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_short_bool_cast' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'single_line_comment_style' => true,
    ])
    ->setFormat('txt')
    ->setFinder($finder)
;
