<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Richard Henkenjohann 2013
 * @author     Richard Henkenjohann
 * @package    Isotope
 * @license    LGPL
 * @filesource
 */


/**
 * Class IsotopeAdditionalSteps
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Richard Henkenjohann 2013
 * @author     Richard Henkenjohann
 * @package    Isotope
 */
class IsotopeAdditionalSteps extends ModuleIsotopeCheckout
{
	/**
	 * The additional steps
	 */
	protected $arrSteps = array();


	/**
	 * Add empty construct function
	 */
	public function __construct() {}


	/**
	 * Get steps as an options array
	 * @return array
	 */
	public function getSteps()
	{
		$arrOptions = array();

		foreach ($GLOBALS['ISO_CHECKOUT_STEPS'] as $k => $v)
		{
			$arrOptions[] = $k;
		}

		return array_keys($arrOptions);
	}


	/**
	 * Update/Add checkout step
	 * @param DataContainer
	 */
	public function updateCheckoutStep(DataContainer $dc)
	{
		$objDatabase = Database::getInstance()->query("SELECT * FROM tl_iso_additional_steps WHERE enabled=1");

		while ($objDatabase->next())
		{
			$this->arrSteps[standardize($this->restoreBasicEntities($objDatabase->name))] = array
			(
				'label'     => $objDatabase->label,
				'settings'  => array
				(
					'position'  => $objDatabase->position,
					'access'    => $objDatabase->access,
					'countries' => deserialize($objDatabase->countries)
				)
			);
		}

		$this->writeStepsFile();
	}


	/**
	 * Generate order review interface and return it as HTML string
	 * @param object
	 * @return string
	 */
	public function generateStepInterface($objCheckout)
	{
		$intInsertId = 0;
		$objDatabase = Database::getInstance()->query("SELECT * FROM tl_iso_additional_steps WHERE enabled=1");

		while ($objDatabase->next())
		{
			if (standardize($this->restoreBasicEntities($objDatabase->name)) == $objCheckout->strCurrentStep)
			{
				$intInsertId = $objDatabase->insert_article;
				break;
			}
		}

		$this->import('Input');
		return ($intInsertId) ? $this->getArticle($intInsertId, false, true) : '';
	}


	/**
	 * Write file with checkout steps
	 * * We have to write the new settings down because the $GLOBALS['ISO_CHECKOUT_STEPS'] will be overwritten
	 */
	protected function writeStepsFile()
	{
		$objFile = new File('system/modules/isotope_additional_steps/config/checkout_steps.php');
		$export = '';

		// Execute each step
		foreach ($this->arrSteps as $step => $arrSettings)
		{
			$t = "\t";
			$label = $arrSettings['label'];
			$position = $arrSettings['settings']['position'];
			$blnDefined = ($arrSettings['settings']['access'] != 'both') ? true : false;

			// Write comment and FE-only condition
			$export .= "\n\n/**\n * Field \"$label\"\n */\n";
			$export .= "if (TL_MODE == 'FE')\n{\n";

			// Write country condition if necessary
			if (!empty($arrSettings['settings']['countries']))
			{
				$export .= $t . "if (in_array(Isotope::getInstance()->Cart->shippingAddress->country, array('" . implode("', '", $arrSettings['settings']['countries']) . "')))\n$t{\n";
				$t .= "\t";
			}

			// Write language label
			$export .= "$t\$GLOBALS['TL_LANG']['ISO']['checkout_$step'] = '$label';\n\n";

			// Write guest/member condition if necessary
			if ($blnDefined)
			{
				$export .= $t . "if (";
				$export .= ($arrSettings['settings']['access'] == 'member') ? '!' : '';
				$export .= "FE_USER_LOGGED_IN)\n$t{\n";

				$t .= "\t";
			}

			// Extend checkout steps array
			$export .= $t . "array_insert(\$GLOBALS['ISO_CHECKOUT_STEPS'], $position, array\n$t(\n";
			$export .= "$t\t'$step' => array(array('IsotopeAdditionalSteps', 'generateStepInterface'))\n$t));";
			$t = substr($t, 0, -1);

			// Finish guest/member condition
			$export .= ($blnDefined) ? "\n$t}" : "";

			// Finish country condition
			if (!empty($arrSettings['settings']['countries']))
			{
				$export .= "\n$t}";
			}

			$export .= "\n}\n";
		}

		$objFile->write('<?php' . $export . "\n\n");
		$objFile->close();
	}
}
