<?php
$result = [
	'sections' => [
		'profile'                => ['label' => 'Hide Profile Fields', 'priority' => 80],
		'sidebar-widgets'        => ['label' => 'Hide Sidebar Widgets', 'priority' => 100],
		'sidebars'               => ['label' => 'Hide Sidebars', 'priority' => 120],
		'gutenberg-general'      => ['label' => 'Gutenberg (Block Editor)', 'priority' => 25],
		'environment-type'       => ['label' => 'Environment Type', 'priority' => 30],
		'disable-customizations' => [
			'label'       => 'Disable Customizations',
			'priority'    => 200,
			'description' =>
				'You can selectively disable some customizations for a role or user. This means the user'
				. ' will see the default, unmodified version of the thing. It doesn\'t prevent the user'
				. ' from editing the relevant settings.'
				. "\n\n"
				. 'Note: "Default" here only means that AME will leave it unchanged. Other plugins can still make changes.',
		],
	],

	'tweaks' => [
		'hide-screen-meta-links' => [
			'label'            => 'Hide screen meta links',
			'selector'         => '#screen-meta-links',
			'hideableLabel'    => 'Screen meta links',
			'hideableCategory' => 'admin-ui',
		],
		'hide-screen-options'    => [
			'label'            => 'Hide the "Screen Options" button',
			'selector'         => '#screen-options-link-wrap',
			'parent'           => 'hide-screen-meta-links',
			'hideableLabel'    => '"Screen Options" button',
			'hideableCategory' => 'admin-ui',
		],
		'hide-help-panel'        => [
			'label'            => 'Hide the "Help" button',
			'selector'         => '#contextual-help-link-wrap',
			'parent'           => 'hide-screen-meta-links',
			'hideableLabel'    => '"Help" button',
			'hideableCategory' => 'admin-ui',
		],
		'hide-all-admin-notices' => [
			'label'            => 'Hide ALL admin notices',
			'selector'         => '#wpbody-content .notice, #wpbody-content .updated, #wpbody-content .update-nag',
			'hideableLabel'    => 'All admin notices',
			'hideableCategory' => 'admin-ui',
		],

		'hide-gutenberg-options'    => [
			'label'         => 'Hide the Gutenberg options menu (three vertical dots)',
			'selector'      => '#editor .edit-post-header__settings .edit-post-more-menu,'
				//WP 6.x
				. ' #editor .edit-post-header__settings .interface-more-menu-dropdown,'
				//WP 6.7.1
				. ' #editor .editor-header__settings .components-dropdown-menu:not(.editor-preview-dropdown):last-child',
			'section'       => 'gutenberg-general',
			'hideableLabel' => 'Gutenberg options menu',
		],
		'hide-gutenberg-fs-wp-logo' => [
			'label'         => 'Hide the WordPress logo in Gutenberg fullscreen mode',
			'selector'      => '#editor .edit-post-header a.components-button[href^="edit.php"]',
			'section'       => 'gutenberg-general',
			'hideableLabel' => 'WordPress logo in Gutenberg fullscreen mode',
		],

		'show-environment-in-toolbar'  => [
			'label'       => 'Show environment type in the Toolbar',
			'section'     => 'environment-type',
			'className'   => 'ameEnvironmentNameTweak',
			'includeFile' => __DIR__ . '/ameEnvironmentNameTweak.php',
		],
		'environment-dependent-colors' => [
			'label'       => 'Change menu color depending on the environment',
			'section'     => 'environment-type',
			'className'   => 'ameEnvironmentColorTweak',
			'includeFile' => __DIR__ . '/ameEnvironmentColorTweak.php',
		],

		'hide-inserter-media-tab' => [
			'label'         => 'Hide the "Media" tab in the block inserter',
			'selector'      => implode(', ', [
				'#editor #tab-panel-0-media',
				//It appears that the tab IDs vary from site to site, and may depend on the order in
				//which the tabs were created/opened. So we try to target multiple versions. Unfortunately,
				//there doesn't seem to be a concise way to target specific block inserter tabs. They
				//don't have any unique classes or data attributes.
				'#editor .editor-inserter-sidebar #tabs-1-media',
				'#editor .editor-inserter-sidebar #tabs-2-media',
				'#editor .editor-inserter-sidebar #tabs-3-media',
				'#editor .editor-inserter-sidebar #tabs-4-media',
			]),
			'section'       => 'gutenberg-general',
			'hideableLabel' => '"Media" tab in the block inserter',
		],

		'hide-block-patterns'        => [
			'label'         => 'Hide block patterns',
			'isGroup'       => true,
			'section'       => 'gutenberg-general',
			'hideableLabel' => 'Block patterns',
		],
		'hide-patterns-tab-with-css' => [
			'label'         => 'Hide the "Patterns" tab in the block inserter',
			'selector'      => implode(', ', [
				'#editor #tab-panel-0-patterns',
				'#editor .editor-inserter-sidebar #tabs-1-patterns',
				'#editor .editor-inserter-sidebar #tabs-2-patterns',
				'#editor .editor-inserter-sidebar #tabs-3-patterns',
				'#editor .editor-inserter-sidebar #tabs-4-patterns',
			]),
			'parent'        => 'hide-block-patterns',
			'section'       => 'gutenberg-general',
			'hideableLabel' => '"Patterns" tab in the block inserter',
		],
		'disable-remote-patterns'    => [
			'label'       => 'Disable remote patterns',
			'className'   => ameDisableRemotePatternsTweak::class,
			'includeFile' => __DIR__ . '/ameDisableRemotePatternsTweak.php',
			'parent'      => 'hide-block-patterns',
			'section'     => 'gutenberg-general',
		],
		'unregister-all-patterns'    => [
			'label'       => 'Unregister all visible patterns (Caution: Also affects "Appearance → Editor")',
			'className'   => ameUnregisterPatternsTweak::class,
			'includeFile' => __DIR__ . '/ameUnregisterPatternsTweak.php',
			'parent'      => 'hide-block-patterns',
			'section'     => 'gutenberg-general',
		],
	],

	'definitionFactories' => [],
];

//region Profile tweaks
/** @noinspection PhpUnused -- Used as a definition factory callback below. */
function ws_ame_get_profile_tweak_defs() {
	$profileScreens = ['profile', 'user-edit'];
	$profileSection = 'profile';
	$profileTweaks = [
		'hide-profile-group-personal-info'   => [
			'label'   => 'Personal Info',
			'isGroup' => true,
		],
		'hide-profile-visual-editor'         => [
			'label'    => 'Visual Editor',
			'selector' => 'tr.user-rich-editing-wrap',
			'parent'   => 'hide-profile-group-personal-info',
		],
		'hide-profile-syntax-highlighting'   => [
			'label'    => 'Syntax Highlighting',
			'selector' => 'tr.user-syntax-highlighting-wrap',
			'parent'   => 'hide-profile-group-personal-info',
		],
		'hide-profile-color-scheme-selector' => [
			'label'    => 'Admin Color Scheme',
			'selector' => 'tr.user-admin-color-wrap',
			'parent'   => 'hide-profile-group-personal-info',
		],
		'hide-profile-keyboard-shortcuts'    => [
			'label'    => 'Keyboard Shortcuts',
			'selector' => 'tr.user-comment-shortcuts-wrap',
			'parent'   => 'hide-profile-group-personal-info',
		],
		'hide-profile-toolbar-toggle'        => [
			'label'    => 'Toolbar',
			'selector' => 'tr.show-admin-bar.user-admin-bar-front-wrap',
			'parent'   => 'hide-profile-group-personal-info',
		],

		'hide-profile-group-name'   => [
			'label'     => 'Name',
			'jquery-js' => 'jQuery("#profile-page tr.user-user-login-wrap").closest("table").prev("h2").addBack().hide();',
		],
		'hide-profile-user-login'   => [
			'label'    => 'Username',
			'selector' => 'tr.user-user-login-wrap',
			'parent'   => 'hide-profile-group-name',
		],
		'hide-profile-first-name'   => [
			'label'    => 'First Name',
			'selector' => 'tr.user-first-name-wrap',
			'parent'   => 'hide-profile-group-name',
		],
		'hide-profile-last-name'    => [
			'label'    => 'Last Name',
			'selector' => 'tr.user-last-name-wrap',
			'parent'   => 'hide-profile-group-name',
		],
		'hide-profile-nickname'     => [
			'label'    => 'Nickname',
			'selector' => 'tr.user-nickname-wrap',
			'parent'   => 'hide-profile-group-name',
		],
		'hide-profile-display-name' => [
			'label'    => 'Display name',
			'selector' => 'tr.user-display-name-wrap',
			'parent'   => 'hide-profile-group-name',
		],

		'hide-profile-group-contact-info' => [
			'label'     => 'Contact Info',
			'jquery-js' => 'jQuery("#profile-page tr.user-email-wrap").closest("table").prev("h2").addBack().hide();',
		],
		'hide-profile-email'              => [
			'label'    => 'Email',
			'selector' => 'tr.user-email-wrap',
			'parent'   => 'hide-profile-group-contact-info',
		],
		'hide-profile-url'                => [
			'label'    => 'Website',
			'selector' => 'tr.user-url-wrap',
			'parent'   => 'hide-profile-group-contact-info',
		],
	];

	//Find user contact methods and add them to the list of hideable profile fields.
	if ( is_callable('wp_get_user_contact_methods') ) {
		$contactMethods = wp_get_user_contact_methods();
		foreach ($contactMethods as $contactMethodId => $contactMethod) {
			$profileTweaks['hide-profile-cm-' . $contactMethodId] = [
				'label'    => $contactMethod,
				'selector' => 'tr.user-' . $contactMethodId . '-wrap',
				'parent'   => 'hide-profile-group-contact-info',
			];
		}
	}

	//"About Yourself" section.
	$profileTweaks = array_merge($profileTweaks, [
		'hide-profile-group-about-yourself' => [
			'label'     => 'About Yourself',
			'jquery-js' => 'jQuery("#profile-page tr.user-description-wrap").closest("table").prev("h2").addBack().hide();',
		],

		'hide-profile-user-description' => [
			'label'    => 'Biographical Info',
			'selector' => 'tr.user-description-wrap',
			'parent'   => 'hide-profile-group-about-yourself',
		],

		'hide-profile-picture' => [
			'label'    => 'Profile Picture',
			'selector' => 'tr.user-profile-picture',
			'parent'   => 'hide-profile-group-about-yourself',
		],
	]);

	$defs = [];
	foreach ($profileTweaks as $tweakId => $tweak) {
		$tweak['section'] = $profileSection;
		$tweak['screens'] = $profileScreens;
		$defs[$tweakId] = $tweak;
	}
	return $defs;
}

$result['definitionFactories'][] = 'ws_ame_get_profile_tweak_defs';
//endregion

//region "Disable Customizations" tweaks
function ws_ame_get_dc_tweak_defs($earlyStage = false) {
	$dcOptions = [];
	if ( $earlyStage ) {
		$dcOptions[WPMenuEditor::ADMIN_MENU_STRUCTURE_COMPONENT] = [
			'Admin menu content',
			'Disables custom permissions, menu order, user-created items, etc. Does not affect global menu styles.',
		];
	} else {
		//Since we do class_exists() checks here, this code should run after all modules have been
		//loaded, not as early as possible.
		if ( class_exists(amePluginVisibility::class, false) ) {
			$dcOptions[amePluginVisibility::CUSTOMIZATION_COMPONENT] = [
				'Plugin list',
				'Disables custom plugin visibility and custom plugin names/descriptions on the "Plugins" page.',
			];
		}
		if ( class_exists(ameWidgetEditor::class, false) ) {
			$dcOptions[ameWidgetEditor::CUSTOMIZATION_COMPONENT] = [
				'Dashboard widgets',
				'Disables custom widget visibility, layout, titles, and user-created widgets.',
			];
		}
		if ( class_exists(ameMetaBoxEditor::class, false) ) {
			$dcOptions[ameMetaBoxEditor::CUSTOMIZATION_COMPONENT] = [
				'Meta boxes',
				'Disables custom meta box visibility in the post editor.',
			];
		}
	}

	$defs = [];
	foreach ($dcOptions as $component => $texts) {
		list($label, $description) = $texts;
		$defs['disable-custom-' . $component] = [
			'label'              => $label,
			'description'        => $description,
			'componentToDisable' => $component,
			'section'            => 'disable-customizations',
			'includeFile'        => __DIR__ . '/ameDisableCustomizationsTweak.php',
			'factory'            => [ameDisableCustomizationsTweak::class, 'create'],
		];
	}
	return $defs;
}

$result['tweaks'] = array_merge($result['tweaks'], ws_ame_get_dc_tweak_defs(true));

$result['definitionFactories'][] = 'ws_ame_get_dc_tweak_defs';
//endregion

return $result;