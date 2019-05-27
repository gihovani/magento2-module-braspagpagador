<?php

namespace Webjump\BraspagPagador\Test\Unit\Model\Payment\Transaction\CreditCard\Ui;

use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider;
use Magento\Framework\Phrase;

class ConfigProviderTest extends \PHPUnit\Framework\TestCase
{
	private $configProvider;

    private $creditCardConfig;

	public function setUp()
	{
        $this->creditCardConfig = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface');

		$this->configProvider = new ConfigProvider(
            $this->creditCardConfig
        );
	}

    public function testGetConfig()
    {
        $this->creditCardConfig->expects($this->once())
            ->method('isSaveCardActive')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isAuthentication3Ds20Active')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isAuthentication3Ds20AuthorizedOnFailure')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('isAuthentication3Ds20AuthorizeOnUnenrolled')
            ->will($this->returnValue(true));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd1')
            ->will($this->returnValue('mdd 1'));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd2')
            ->will($this->returnValue('mdd 2'));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd3')
            ->will($this->returnValue('mdd 3'));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd4')
            ->will($this->returnValue('mdd 4'));

        $this->creditCardConfig->expects($this->once())
            ->method('getAuthenticate3Ds20Mdd5')
            ->will($this->returnValue('mdd 5'));

        static::assertEquals(
            [
                'payment' => [
                    'ccform' => [
                        'savecard' => [
                            'active' => ['braspag_pagador_creditcard' => true]
                        ],

                        'bpmpi_authenticate' => [
                            'active' => true,
                            'authorize_on_failure' => true,
                            'authorize_on_unenrolled' => true,
                            'mdd1' => 'mdd 1',
                            'mdd2' => 'mdd 2',
                            'mdd3' => 'mdd 3',
                            'mdd4' => 'mdd 4',
                            'mdd5' => 'mdd 5'
                        ]
                    ]
                ]
            ],
            $this->configProvider->getConfig()
        );
    }
}
