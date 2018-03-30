<?php
/*
 * Copyright(c) 2018 izayoi256 All Rights Reserved.
 *
 * This software is released under the MIT license.
 * http://opensource.org/licenses/mit-license.php
 */

namespace izayoi256\Nem\Test\Model;

use izayoi256\Nem\Model\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    /**
     * @return array
     */
    public function provideValidAddressValues()
    {
        return array(
            array('TDZMWOCMABLGHB37KJJLVXRUXSMOXR4K4PUMZRTA'),
            array('TB6EFML3BRJOMNVLW7K3RVJSBPQIEOSPL3EV3PE6'),
            array('NARMZPEN6RMA7CWCKBHPT5BS44BKUK3QWE7EW7WD'),
            array('TDZMWO-CMABLG-HB37KJ-JLVXRU-XSMOXR-4K4PUM-ZRTA'),
            array('-T-B-6-E-F-M-L-3-B-R-J-O-M-N-V-L-W-7-K-3-R-V-J-S-B-P-Q-I-E-O-S-P-L-3-E-V-3-P-E-6-'),
            array('----------NARMZPEN6RMA7CWCKB----HPT5BS44BKUK3QWE7EW7WD------------'),
        );
    }

    /**
     * @dataProvider provideValidAddressValues
     * @param $address
     */
    public function testIsValidAddress($address)
    {
        $this->assertTrue(Address::isValidAddress($address));
    }

    /**
     * @return array
     */
    public function provideInvalidAddressValues()
    {
        return array(
            array(''),
            array('TDZMWOCMABLGHB37KJJLVXRUXSMOXR4K4PUMZRTB'),
            array('TB6EFML3BRJOMNVLW7K3RVJSBPQIEOSPL3EV3PE7'),
            array('NARMZPEN6RMA7CWCKBHPT5BS44BKUK3QWE7EW7WC'),
            array('TDZMWO_CMABLG_HB37KJ_JLVXRU_XSMOXR_4K4PUM_ZRTA'),
            array('TB6EFML3BRJOMNVLW7K3RVJSBPQIEOSPL3EV3PE'),
            array('MARMZPEN6RMA7CWCKBHPT5BS44BKUK3QWE7EW7WD'),
        );
    }

    /**
     * @dataProvider provideInvalidAddressValues
     * @param $address
     */
    public function testIsInvalidAddress($address)
    {
        $this->assertFalse(Address::isValidAddress($address));
    }

    /**
     * @return array
     */
    public function provideValidNetworkAddressValues()
    {
        return array(
            array('TDZMWOCMABLGHB37KJJLVXRUXSMOXR4K4PUMZRTA', array(Address::NETWORK_ID_TESTNET)),
            array('TB6EFML3BRJOMNVLW7K3RVJSBPQIEOSPL3EV3PE6', array(Address::NETWORK_ID_TESTNET)),
            array('NARMZPEN6RMA7CWCKBHPT5BS44BKUK3QWE7EW7WD', array(Address::NETWORK_ID_MAINNET)),
            array('TDZMWOCMABLGHB37KJJLVXRUXSMOXR4K4PUMZRTA', array()),
            array('TB6EFML3BRJOMNVLW7K3RVJSBPQIEOSPL3EV3PE6', array(Address::NETWORK_ID_TESTNET, Address::NETWORK_ID_MAINNET)),
            array('NARMZPEN6RMA7CWCKBHPT5BS44BKUK3QWE7EW7WD', array(10 => Address::NETWORK_ID_TESTNET, 'hoge' => Address::NETWORK_ID_MAINNET)),
        );
    }

    /**
     * @dataProvider provideValidNetworkAddressValues
     * @param $address
     * @param $networkIds
     */
    public function testIsValidNetworkAddress($address, $networkIds)
    {
        $this->assertTrue(Address::isValidAddress($address, $networkIds));
    }

    /**
     * @return array
     */
    public function provideInvalidNetworkAddressValues()
    {
        return array(
            array('TDZMWOCMABLGHB37KJJLVXRUXSMOXR4K4PUMZRTA', array(Address::NETWORK_ID_MAINNET)),
            array('TB6EFML3BRJOMNVLW7K3RVJSBPQIEOSPL3EV3PE6', array(Address::NETWORK_ID_MIJINNET)),
            array('NARMZPEN6RMA7CWCKBHPT5BS44BKUK3QWE7EW7WD', array(Address::NETWORK_ID_TESTNET)),
            array('TDZMWOCMABLGHB37KJJLVXRUXSMOXR4K4PUMZRTA', array(10)),
            array('TB6EFML3BRJOMNVLW7K3RVJSBPQIEOSPL3EV3PE6', array(10, 100000000)),
            array('NARMZPEN6RMA7CWCKBHPT5BS44BKUK3QWE7EW7WD', array(10, 100000000, -100000000)),
        );
    }

    /**
     * @dataProvider provideInvalidNetworkAddressValues
     * @param $address
     * @param $networkIds
     */
    public function testIsInvalidNetworkAddress($address, $networkIds)
    {
        $this->assertTrue(Address::isValidAddress($address));
        $this->assertFalse(Address::isValidAddress($address, $networkIds));
    }

    /**
     * @return array
     */
    public function provideInvalidArgumentAddressValues()
    {
        return array(
            array(true),
            array(null),
            array(array()),
            array(new \stdClass()),
            array(new \Exception('TDZMWOCMABLGHB37KJJLVXRUXSMOXR4K4PUMZRTA')), // object implementing __toString().
        );
    }

    /**
     * @dataProvider provideInvalidArgumentAddressValues
     * @param $address
     */
    public function testIsValidAddress_invalidAddress($address)
    {
        try {
            Address::isValidAddress($address);
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * @return array
     */
    public function provideInvalidArgumentNetworkIdsValues()
    {
        return array(
            array(array('1')),
            array(array(null)),
            array(array(true)),
            array(array(new \stdClass())),
            array(array(array())),
        );
    }

    /**
     * @dataProvider provideInvalidArgumentNetworkIdsValues
     * @param $networkIds
     */
    public function testIsValidAddress_invalidNetworkIds($networkIds)
    {
        try {
            Address::isValidAddress('', $networkIds);
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }
}
