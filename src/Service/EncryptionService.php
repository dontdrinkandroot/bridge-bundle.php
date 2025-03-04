<?php

namespace Dontdrinkandroot\BridgeBundle\Service;

use Dontdrinkandroot\Common\Asserted;

use function random_bytes;

use const SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES;

class EncryptionService
{
    public function generateKey(): string
    {
        return sodium_crypto_aead_xchacha20poly1305_ietf_keygen();
    }

    public function generateNonce(): string
    {
        return random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
    }

    public function encrypt(string $message, string $key, string $nonce): string
    {
        return sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($message, '', $nonce, $key);
    }

    public function decrypt(string $message, string $key, string $nonce): string
    {
        return Asserted::notFalse(sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($message, '', $nonce, $key));
    }
}
