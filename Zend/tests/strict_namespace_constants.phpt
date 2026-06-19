--TEST--
declare(strict_namespace=1) disables global fallback for unqualified constants
--FILE--
<?php
declare(strict_namespace=1);

namespace Foo;

const GREETING = 'foo-greeting';        // Defines Foo\GREETING
\define('BAREWORD', 'global-bareword'); // Defines the global constant

// Unqualified constant resolves to Foo\GREETING, not the global namespace
\var_dump(GREETING);

// Fully qualified still reaches the global constant
\var_dump(\BAREWORD);

// Global-only constant: unqualified BAREWORD reads as Foo\BAREWORD (undefined),
// with no fallback to the global
try {
    \var_dump(BAREWORD);
} catch (\Error $e) {
    echo $e->getMessage(), "\n";
}

// PHP_INT_MAX (a built-in compile-time constant) also reads as Foo\PHP_INT_MAX,
// not the global; fully qualified works
try {
    \var_dump(PHP_INT_MAX);
} catch (\Error $e) {
    echo $e->getMessage(), "\n";
}
\var_dump(\is_int(\PHP_INT_MAX));

// Constant expressions follow the same rule: PREFIX resolves to Foo\PREFIX
const PREFIX = 'p-';
const COMBINED = PREFIX . 'x';
\var_dump(COMBINED);

// true, false and null are language literals, never namespace-resolved
\var_dump(null, true, false);
?>
--EXPECT--
string(12) "foo-greeting"
string(15) "global-bareword"
Undefined constant "Foo\BAREWORD"
Undefined constant "Foo\PHP_INT_MAX"
bool(true)
string(3) "p-x"
NULL
bool(true)
bool(false)
