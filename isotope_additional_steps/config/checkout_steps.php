<?php

/**
 * Field "Informationen"
 */
if (TL_MODE == 'FE')
{
	if (in_array(Isotope::getInstance()->Cart->shippingAddress->country, array('ch')))
	{
		$GLOBALS['TL_LANG']['ISO']['checkout_schweizer-versand-info'] = 'Informationen';

		array_insert($GLOBALS['ISO_CHECKOUT_STEPS'], 1, array
		(
			'schweizer-versand-info' => array(array('IsotopeAdditionalSteps', 'generateStepInterface'))
		));
	}
}


/**
 * Field "Bonuspunkte"
 */
if (TL_MODE == 'FE')
{
	$GLOBALS['TL_LANG']['ISO']['checkout_hinweis-bonuspunktesystem'] = 'Bonuspunkte';

	if (FE_USER_LOGGED_IN)
	{
		array_insert($GLOBALS['ISO_CHECKOUT_STEPS'], 1, array
		(
			'hinweis-bonuspunktesystem' => array(array('IsotopeAdditionalSteps', 'generateStepInterface'))
		));
	}
}


