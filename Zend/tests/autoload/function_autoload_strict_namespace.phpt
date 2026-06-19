--TEST--
declare(strict_namespace=1) disables global fallback for unqualified function calls
--FILE--
<?php
declare(strict_namespace=1);

namespace Foo;

// Bare names are Foo\... now, so every global call below is fully qualified
\spl_autoload_register_function_loader(function (string $name) {
    echo "loader($name)\n";
    if ($name === 'Foo\strlen') {
        eval('namespace Foo; function strlen($s) { return -1; }');
    }
});

// Unqualified -> Foo\strlen, which autoloads; would be global strlen otherwise
\var_dump(strlen('hello'));

// Fully qualified still reaches the global function directly, no autoload
\var_dump(\strlen('hello'));

// A second unqualified call site now binds to the loaded Foo\strlen
\var_dump(strlen('hi'));
?>
--EXPECT--
loader(Foo\strlen)
int(-1)
int(5)
int(-1)
