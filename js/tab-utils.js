jQuery(function ($) {
	var menuEditorHeading = $('#ws_ame_editor_heading').first();
	var pageWrapper = menuEditorHeading.closest('.wrap');
	var tabList = pageWrapper.find('.nav-tab-wrapper').first();

	//On AME pages, move settings tabs after the heading. This is necessary to make them appear on the right side,
	//and WordPress breaks that by moving notices like "Settings saved" after the first H1 (see common.js).
	var menuEditorTabs = tabList.add(tabList.next('.clear'));
	if ((menuEditorHeading.length > 0) && (menuEditorTabs.length > 0)) {
		menuEditorTabs.insertAfter(menuEditorHeading);
	}

	//Add size classes to each tab to enable fixed-increment resizing.
	const tabColumnWidth = parseInt((tabList.css('--ame-tab-col-width') || '32px').replace('px', ''), 10);
	const tabCondensedHorizontalPadding = parseInt((tabList.css('--ame-tab-cnd-horizontal-padding') || '8px').replace('px', ''), 10);
	const tabCondensedGap = parseInt((tabList.css('--ame-tab-cnd-gap') || '5px').replace('px', ''), 10);

	function calculateColumns(contentWidth, condensedPadding) {
		if (typeof condensedPadding === 'undefined') {
			condensedPadding = tabCondensedHorizontalPadding;
		}
		//Calculate the lowest number of columns that would fit a tab with the given content width.
		//Minimum width = content width + padding for condensed tabs + border.
		const condensedWidth = contentWidth + condensedPadding * 2 + 2;
		return Math.min(Math.ceil(
			(condensedWidth + tabCondensedGap) / (tabColumnWidth + tabCondensedGap)
		), 12);
	}

	tabList.children('.nav-tab').each(function () {
		const $this = $(this);
		const columnCount = calculateColumns($this.width());
		$this.addClass('ame-nav-tab-col-' + columnCount);
	});

	//Also add a size class to the heading that's inside the tab list. That heading starts hidden,
	//so we use the size of the main heading to determine the column count.
	const $inlineHeading = tabList.find('#ws_ame_tab_leader_heading');
	if (($inlineHeading.length > 0) && (menuEditorHeading.length > 0)) {
		const headingColumnCount = calculateColumns(menuEditorHeading.width(), 0);
		$inlineHeading.addClass('ame-nav-tab-col-' + headingColumnCount);
	}

	//Switch tab styles when there are too many tabs and they don't fit on one row.
	var $firstTab = null,
		$lastTab = null,
		knownTabWrapThreshold = -1;

	function updateTabStyles() {
		if (($firstTab === null) || ($lastTab === null)) {
			var $tabItems = tabList.children('.nav-tab');
			$firstTab = $tabItems.first();
			$lastTab = $tabItems.last();
		}

		//To detect if any tabs are wrapped to the next row, check if the top of the last tab
		//is below the bottom of the first tab.
		var firstPosition = $firstTab.position();
		var lastPosition = $lastTab.position();
		var windowWidth = $(window).width();
		//Sanity check.
		if (
			!firstPosition || !lastPosition || !windowWidth
			|| (typeof firstPosition['top'] === 'undefined')
			|| (typeof lastPosition['top'] === 'undefined')
		) {
			return;
		}
		var firstTabBottom = firstPosition.top + $firstTab.outerHeight();
		//Note: The -1 below is due to the active tab having a negative bottom margin.
		var areTabsWrapped = (lastPosition.top >= (firstTabBottom - 1));

		//Tab positions may change when we apply different styles, which could lead to the tab bar
		//rapidly cycling between one and two rows when the browser width is just right.
		//To prevent that, remember what the width was when we detected wrapping, and always apply
		//the alternative styles if the width is lower than that.
		var wouldWrapByDefault = (windowWidth <= knownTabWrapThreshold);

		var tooManyTabs = areTabsWrapped || wouldWrapByDefault;
		if (tooManyTabs && (windowWidth > knownTabWrapThreshold)) {
			knownTabWrapThreshold = windowWidth;
		}

		pageWrapper.toggleClass('ws-ame-too-many-tabs', tooManyTabs);
	}

	updateTabStyles();

	menuEditorHeading.css('visibility', 'visible');
	tabList.css('visibility', 'visible');

	$(window).on('resize', wsAmeLodash.debounce(
		function () {
			updateTabStyles();
		},
		300
	));
});

