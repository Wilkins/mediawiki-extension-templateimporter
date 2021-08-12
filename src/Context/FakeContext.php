<?php

namespace TemplateImporter\Context;

class FakeContext {

    public function msg( $text ) {
        return new FakeMessage( $text );
    }

}
