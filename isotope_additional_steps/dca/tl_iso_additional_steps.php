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
 * Table tl_iso_additional_steps
 */
$GLOBALS['TL_DCA']['tl_iso_additional_steps'] = array
(
	// Config
	'config' => array
	(
		'dataContainer' => 'Table',
		'enableVersioning' => true,
		'closed' => true,
		'onload_callback' => array
		(
			array('IsotopeBackend', 'initializeSetupModule')
		),
		'onsubmit_callback' => array
		(
			array('IsotopeAdditionalSteps', 'updateCheckoutStep')
		),
		'ondelete_callback' => array
		(
			array('IsotopeAdditionalSteps', 'updateCheckoutStep')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode' => 1,
			'fields' => array('name'),
			'flag' => 1,
			'panelLayout' => 'sort,filter;search,limit'
		),
		'label' => array
		(
			'fields' => array('name', 'type'),
			'format' => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
			'label_callback' => array('IsotopeBackend', 'addPublishIcon')
		),
		'global_operations' => array
		(
			'back' => array(
				'label' => &$GLOBALS['TL_LANG']['MSC']['backBT'],
				'href' => 'mod=&table=',
				'class' => 'header_back',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
			'new' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['new'],
				'href' => 'act=create',
				'class' => 'header_new',
				'attributes' => 'onclick="Backend.getScrollOffset();"',
			),
			'all' => array(
				'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href' => 'act=select',
				'class' => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			),
		),
		'operations' => array
		(
			'edit' => array(
				'label' => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['edit'],
				'href' => 'act=edit',
				'icon' => 'edit.gif'
			),
			'delete' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['delete'],
				'href' => 'act=delete',
				'icon' => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['show'],
				'href' => 'act=show',
				'icon' => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default' => '{title_legend},name,label,display;{config_legend},position,access,countries,insert_article;{enabled_legend},enabled'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['name'],
			'exclude'               => true,
			'search'                => true,
			'inputType'             => 'text',
			'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'label' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['label'],
			'exclude'               => true,
			'inputType'             => 'text',
			'eval'                  => array('maxlength'=>255, 'tl_class'=>'w50'),
		),
		'position' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['position'],
			'exclude'               => true,
			'inputType'             => 'select',
			'options_callback'      => array('IsotopeAdditionalSteps', 'getSteps'),
			'eval'                  => array('tl_class' => 'w50')
		),
		'access' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_module']['iso_checkout_method'],
			'exclude'               => true,
			'inputType'             => 'select',
			'default'				=> 'both',
			'options'			    => array('member', 'guest', 'both'),
			'reference'				=> &$GLOBALS['TL_LANG']['tl_module']['iso_checkout_method_ref']
		),
		'countries' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['countries'],
			'exclude'               => true,
			'inputType'             => 'select',
			'options'               => $this->getCountries(),
			'eval'                  => array('multiple'=>true, 'size'=>8, 'tl_class'=>'w50', 'chosen'=>true)
		),
		'insert_article' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['insert_article'],
			'exclude'               => true,
			'inputType'             => 'text',
			'eval'                  => array('maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50')
		),
		'enabled' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_iso_additional_steps']['enabled'],
			'exclude'               => true,
			'inputType'             => 'checkbox'
		)
	)
);
