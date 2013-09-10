<?php

class JOSE_JWE_Test extends JOSE_TestCase {
    var $plain_text;
    var $rsa_keys;

    function setUp() {
        parent::setUp();
        $this->plain_text = 'Hello World';
    }

    function testEncryptRSA15_A128CBCHS256() {
        $jwe = new JOSE_JWE($this->plain_text);
        $jwe->encrypt($this->rsa_keys['public']);
        $jwe_decoded = JOSE_JWT::decode($jwe->toString());
        $this->assertEquals($this->plain_text, $jwe_decoded->decrypt($this->rsa_keys['private'])->plain_text);
    }

    function testEncryptRSA15_A256CBCHS512() {
        $jwe = new JOSE_JWE($this->plain_text);
        $jwe->encrypt($this->rsa_keys['public'], 'RSA1_5', 'A256CBC+HS512');
        $jwe_decoded = JOSE_JWT::decode($jwe->toString());
        $this->assertEquals($this->plain_text, $jwe_decoded->decrypt($this->rsa_keys['private'])->plain_text);
    }

    function testEncryptRSA15_A128GCM() {
        $jwe = new JOSE_JWE($this->plain_text);
        $this->setExpectedException('JOSE_Exception_UnexpectedAlgorithm');
        $jwe->encrypt($this->rsa_keys['public'], 'RSA1_5', 'A128GCM');
    }

    function testEncryptRSA15_A256GCM() {
        $jwe = new JOSE_JWE($this->plain_text);
        $this->setExpectedException('JOSE_Exception_UnexpectedAlgorithm');
        $jwe->encrypt($this->rsa_keys['public'], 'RSA1_5', 'A256GCM');
    }

    function testEncryptRSAOAEP_A128CBCHS256() {
        $jwe = new JOSE_JWE($this->plain_text);
        $jwe->encrypt($this->rsa_keys['public'], 'RSA-OAEP');
        $jwe_decoded = JOSE_JWT::decode($jwe->toString());
        $this->assertEquals($this->plain_text, $jwe_decoded->decrypt($this->rsa_keys['private'])->plain_text);
    }

    function testEncryptRSAOAEP_A256CBCHS512() {
        $jwe = new JOSE_JWE($this->plain_text);
        $jwe->encrypt($this->rsa_keys['public'], 'RSA-OAEP', 'A256CBC+HS512');
        $jwe_decoded = JOSE_JWT::decode($jwe->toString());
        $this->assertEquals($this->plain_text, $jwe_decoded->decrypt($this->rsa_keys['private'])->plain_text);
    }

    function testEncryptA128KW_A128CBCHS256() {
        $jwe = new JOSE_JWE($this->plain_text);
        $this->setExpectedException('JOSE_Exception_UnexpectedAlgorithm');
        $jwe->encrypt($this->rsa_keys['public'], 'A128KW');
    }

    function testEncryptDir_A128CBCHS256() {
        $jwe = new JOSE_JWE($this->plain_text);
        $this->setExpectedException('JOSE_Exception_UnexpectedAlgorithm');
        $jwe->encrypt($this->rsa_keys['public'], 'dir');
    }

    function testEncryptRSA15_Unknown() {
        $jwe = new JOSE_JWE($this->plain_text);
        $this->setExpectedException('JOSE_Exception_UnexpectedAlgorithm');
        $jwe->encrypt($this->rsa_keys['public'], 'RSA1_5', 'Unknown');
    }

    function testEncryptUnknown_A128CBCHS256() {
        $jwe = new JOSE_JWE($this->plain_text);
        $this->setExpectedException('JOSE_Exception_UnexpectedAlgorithm');
        $jwe->encrypt($this->rsa_keys['public'], 'Unknown');
    }

    function testDecryptRSA15_A128CBCHS256() {
        $input = 'eyJhbGciOiJSU0ExXzUiLCJlbmMiOiJBMTI4Q0JDK0hTMjU2In0.gOIfTaAkLJYGsK-anmDgxokNit2UqKiraKyExUxM0oj5mw2UngEUGvLK-iztMTiONovqwsMmxOsoZLt_t7paCAx1_3KB1YuCZtBF-0_eH54j0KRdF1Ht8xDaPg0nNmkfSqn19EM-fZVDNBK4Jig-8eF0nbtq1EjL9m6AXV1utIQgM5-3gDtnXkNJ8pYkS22Lga4906smr5IkswdlJEvu81GFV7haFb1Edpyw_Ty0El8KW-p0Udz5FFZD_16qO4FzvSJk73X2l21zXENqUXhiFJDXacBOozpyGL0Rf-idwk9-X3mh8RThutcTelVUOWYdcW-7B8oLaeLEPFYeaLLsjQ.AaxiImKsfoBGoM5s9bp90Q.5KBllDM4n5Po3BhQ8CkpTQ.MNpTRLD3plIxs6JqR6h2ww0D97T5R9oNtE7uplkUcdE';
        $jwe = JOSE_JWE::decode($input);
        $jwe->decrypt($this->rsa_keys['private']);
        $this->assertEquals($this->plain_text, $jwe->plain_text);
    }

    function testDecode() {
        $input = 'eyJhbGciOiJSU0ExXzUiLCJlbmMiOiJBMTI4Q0JDK0hTMjU2In0.gOIfTaAkLJYGsK-anmDgxokNit2UqKiraKyExUxM0oj5mw2UngEUGvLK-iztMTiONovqwsMmxOsoZLt_t7paCAx1_3KB1YuCZtBF-0_eH54j0KRdF1Ht8xDaPg0nNmkfSqn19EM-fZVDNBK4Jig-8eF0nbtq1EjL9m6AXV1utIQgM5-3gDtnXkNJ8pYkS22Lga4906smr5IkswdlJEvu81GFV7haFb1Edpyw_Ty0El8KW-p0Udz5FFZD_16qO4FzvSJk73X2l21zXENqUXhiFJDXacBOozpyGL0Rf-idwk9-X3mh8RThutcTelVUOWYdcW-7B8oLaeLEPFYeaLLsjQ.AaxiImKsfoBGoM5s9bp90Q.5KBllDM4n5Po3BhQ8CkpTQ.MNpTRLD3plIxs6JqR6h2ww0D97T5R9oNtE7uplkUcdE';
        $jwe = JOSE_JWE::decode($input);
        $this->assertNull($jwe->plain_text);
        $this->assertEquals(array(
            "alg" => "RSA1_5",
            "enc" => "A128CBC+HS256"
        ), $jwe->header);
    }
}
