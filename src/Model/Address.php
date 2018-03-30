<?php
/*
 * Copyright(c) 2018 izayoi256 All Rights Reserved.
 *
 * This software is released under the MIT license.
 * http://opensource.org/licenses/mit-license.php
 */

namespace izayoi256\Nem\Model;

use Base32\Base32;
use izayoi256\Keccak;

class Address
{
    const NETWORK_ID_MAINNET = 104;
    const NETWORK_ID_MIJINNET = 96;
    const NETWORK_ID_TESTNET = -104;

    /**
     * @param string $address
     * @param array $networkIds
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public static function isValidAddress($address, array $networkIds = array())
    {
        if (!is_string($address)) {
            throw new \InvalidArgumentException('Address must be string.');
        }

        if (!is_array($networkIds) || $networkIds !== array_filter($networkIds, 'is_int')) {
            throw new \InvalidArgumentException('Network IDs must be an array of integer.');
        }

        $str = str_replace('-', '', $address);

        if (strlen($str) !== 40) {
            return false;
        }

        $decoded = bin2hex(Base32::decode($str));
        $hash = substr($decoded, 0, 42);
        $bin = pack('H*', $hash);
        $checksum = substr(Keccak::hash($bin, 256), 0, 8);

        if (substr($decoded, 42) !== $checksum) {
            return false;
        }

        $networkValid = !count($networkIds);

        foreach ($networkIds as $networkId) {
            $hex = bin2hex(pack('c', $networkId));
            $networkValid = $networkValid || preg_match("/^{$hex}/", $decoded);
        }

        return $networkValid;
    }
}
