<?php

namespace Kspu\LdapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KspuLdapBundle extends Bundle {
    public function getParent() {
        return 'FR3DLdapBundle';
    }
}
