# Class Proxys

In this folder contains class proxys.
A class proxy extends a given class, like `EasyRdf\Resource`, and adds additional markup / PHPDoc.
It is used to "fix" peculiarities of EasyRdf to reach max PHPStan level.
For instance, EasyRdf allows magic properties being created on the fly (e.g. in `EasyRdf\Resource`).
That may lead to errors like `Access to an undefined property EasyRdf\Resource::$test`.
A proxy class must not alter its parent in any way.
