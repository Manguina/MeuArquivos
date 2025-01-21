<?php
namespace Source\Api\Core;
class AsymmetricCrypto {
    /**
     * Gera um par de chaves pública e privada
     * 
     * @param int $bits Tamanho da chave (padrão 2048)
     * @return array Contendo 'public_key' e 'private_key'
     * @throws Exception Se houver falha na geração de chaves
     */
    public static function generateKeyPair($bits = 2048) {
        // Verifica se a extensão OpenSSL está disponível
        if (!extension_loaded('openssl')) {
            throw new Exception("Extensão OpenSSL não está carregada");
        }

        // Configurações para geração de chaves
        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => $bits,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        // Gera o par de chaves
        $res = openssl_pkey_new($config);

        if ($res === false) {
            throw new Exception("Falha ao gerar chaves. Detalhes: " . openssl_error_string());
        }

        // Exporta a chave privada
        $privateKey = '';
        $passphrase = ''; // Sem senha para a chave privada
        $exportSuccess = openssl_pkey_export($res, $privateKey, $passphrase);

        if (!$exportSuccess) {
            throw new Exception("Falha ao exportar chave privada. Detalhes: " . openssl_error_string());
        }

        // Obtém a chave pública
        $publicKeyDetails = openssl_pkey_get_details($res);
        
        if (!$publicKeyDetails) {
            throw new Exception("Falha ao obter detalhes da chave pública");
        }

        return [
            'public_key' => $publicKeyDetails['key'],
            'private_key' => $privateKey
        ];
    }

    /**
     * Encripta dados usando a chave pública
     * 
     * @param string $data Dados a serem encriptados
     * @param string $publicKey Chave pública para encriptação
     * @return string Dados encriptados em base64
     * @throws Exception Se houver falha na encriptação
     */
    public static function encrypt($data) {

        // Verifica se a extensão OpenSSL está disponível
        $publicKey=file_get_contents(dirname(__DIR__).DIRECTORY_SEPARATOR."Core".DIRECTORY_SEPARATOR."public.pem");
           
        if (!extension_loaded('openssl')) {
            throw new Exception("Extensão OpenSSL não está carregada");
        }

        // Verifica se os dados e a chave não estão vazios
        if (strlen($data)==0) {
            throw new \Exception("Dados para encriptação não podem estar vazios");
        }

        // Verifica se a chave pública é válida
        $publicKeyResource = openssl_pkey_get_public($publicKey);
        if ($publicKeyResource === false) {
            throw new \Exception("Chave pública inválida. Detalhes: " . openssl_error_string());
        }

        // Encriptação
        $encryptedData = '';
        $encrypt = openssl_public_encrypt(
            $data, 
            $encryptedData, 
            $publicKeyResource, 
            OPENSSL_PKCS1_OAEP_PADDING
        );

        // Libera o recurso da chave
        openssl_free_key($publicKeyResource);

        // Verifica se a encriptação foi bem-sucedida
        if ($encrypt === false) {
            throw new \Exception("Falha na encriptação. Detalhes: " . openssl_error_string());
        }

        // Retorna os dados encriptados em base64
        return base64_encode($encryptedData);
    }

    /**
     * Descriptografa dados usando a chave privada
     * 
     * @param string $encryptedData Dados encriptados em base64
     * @param string $privateKey Chave privada para descriptografia
     * @return string Dados descriptografados
     * @throws Exception Se houver falha na descriptografia
     */
    public static function decrypt($encryptedData) {
        // Verifica se a extensão OpenSSL está disponível
        $privateKey=file_get_contents(dirname(__DIR__).DIRECTORY_SEPARATOR."Core".DIRECTORY_SEPARATOR."private.pem");
        if (!extension_loaded('openssl')) {
            throw new Exception("Extensão OpenSSL não está carregada");
        }

        // Verifica se os dados encriptados não estão vazios
        if (empty($encryptedData)) {
            throw new Exception("Dados encriptados não podem estar vazios");
        }

        // Verifica se a chave privada é válida
        $privateKeyResource = openssl_pkey_get_private($privateKey);
        if ($privateKeyResource === false) {
            throw new Exception("Chave privada inválida. Detalhes: " . openssl_error_string());
        }

        // Decodifica os dados base64
        $encryptedBinary = base64_decode($encryptedData);
        if ($encryptedBinary === false) {
            throw new \Exception("Falha ao decodificar dados base64");
        }

        // Descriptografia
        $decryptedData = '';
        $decrypt = openssl_private_decrypt(
            $encryptedBinary, 
            $decryptedData, 
            $privateKeyResource, 
            OPENSSL_PKCS1_OAEP_PADDING
        );

        // Libera o recurso da chave
        openssl_free_key($privateKeyResource);

        // Verifica se a descriptografia foi bem-sucedida
        if ($decrypt === false) {
            throw new \Exception("Falha na descriptografia. Detalhes: " . openssl_error_string());
        }

        return $decryptedData;
    }
}