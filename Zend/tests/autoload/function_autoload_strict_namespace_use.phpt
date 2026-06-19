--TEST--
declare(strict_namespace=1): use function imports still reach global functions
--FILE--
<?php
declare(strict_namespace=1);

namespace Foo;

use function strlen;
use function str_repeat as repeat;

\spl_autoload_register_function_loader(function (string $name) {
    echo "loader($name)\n";
});

// Imported: resolves to the global \strlen via the import, no autoload
\var_dump(strlen('hello'));

// Aliased import likewise reaches global \str_repeat
\var_dump(repeat('ab', 3));

// A non-imported bare name is Foo\..., so it autoloads, then fails as undefined
try {
    missing_helper();
} catch (\Error $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECT--
int(5)
string(6) "ababab"
loader(Foo\missing_helper)
Call to undefined function Foo\missing_helper()
