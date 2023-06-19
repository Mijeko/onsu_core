function setRelated(value, related){
	var target = $(related.selector);
	var state = related.states[value];

	state && state.forEach(function(cur){
		target[cur.action].apply(target, cur.arguments || []);
	})
}

b2.rel.menuHitsEnabled = [
	{
		selector: '#menu-hits-pos-wrap',
		states: {
			'true': [
				{
					action: 'removeClass',
					arguments: ['hidden']
				}
			],
			'false': [
				{
					action: 'addClass',
					arguments: ['hidden']
				},
			]
		},
	},
];

b2.rel.bs_curBlock = [
	{
		selector: '#bs_text-align-wrap',
		states: {
			'text':  [{ action: 'show'}],
			'media': [{ action: 'hide'}]
		}
	},
];

b2.rel.stores = [
	{
		selector: '#show-stock',
		states:{
			'enabled': [{ action: 'hide'}],
			'disabled': [{ action: 'show'}]
		}
	}
];

b2.rel.containerWidth = [
	{
		selector: '#limit-sliders',
		states:{
			'container': [{ action: 'show'}],
			'full_width': [{ action: 'hide'}]
		}
	}
];

b2.rel.categoriesView = [
    {
        selector: '#categories-with-sub',
        states: {
            'list': [{ action: 'show' }],
            'blocks': [{ action: 'hide' }]
        }
    },
    {
        selector: '#categories-with-img',
        states: {
            'list': [{ action: 'hide' }],
            'blocks': [{ action: 'show' }]
        }
    }
];

b2.rel.customTheme = [
	{
		selector: '#settings_general',
		states: {
			true: [{
				action: 'attr',
				arguments: ['data-using-theme', 'use-custom']
			}],
			false: [{
				action: 'attr',
				arguments: ['data-using-theme', 'use-themes']
			}]
		}
	}
];