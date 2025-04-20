<?php

namespace YahnisElsts\AdminMenuEditor\Customizable\Schemas;

use YahnisElsts\AdminMenuEditor\ProCustomizable\Settings\WithSchema\CssPropertySetting;
use YahnisElsts\AdminMenuEditor\ProCustomizable\Settings\WithSchema\Font;

class SchemaFactory {
	public function string($label = null) {
		return new StringSchema($label);
	}

	public function boolean($label = null) {
		return new Boolean($label);
	}

	public function number($label = null) {
		return new Number($label);
	}

	public function int($label = null) {
		return (new Number($label))->int();
	}

	public function enum(array $values, $label = null) {
		return (new Enum($label))->values($values);
	}

	public function struct(array $fieldSchemas, $label = null) {
		return new Struct($fieldSchemas, $label);
	}

	public function cssColor($label = null) {
		return (new Color($label))->orTransparent()->settingClassHint(CssPropertySetting::class);
	}

	public function cssFont($label = null) {
		return (new PlaceholderStruct($label))->settingClassHint(Font::class);
	}
}